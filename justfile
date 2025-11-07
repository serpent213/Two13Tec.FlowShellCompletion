# Package automation for Two13Tec.FlowShellCompletion

default:
  @just --list

# Run unit tests
test-unit:
  #!/usr/bin/env bash
  set -euo pipefail
  if [ -d Tests/Unit ]; then
    (cd ../.. && FLOW_CONTEXT=Testing ./bin/phpunit --configuration=Build/BuildEssentials/PhpUnit/UnitTests.xml -- DistributionPackages/Two13Tec.FlowShellCompletion/Tests/Unit)
  else
    echo "No unit tests defined for Two13Tec.FlowShellCompletion."
  fi

# Run functional tests
test-func:
  #!/usr/bin/env bash
  set -euo pipefail
  if [ -d Tests/Functional ]; then
    (cd ../.. && FLOW_CONTEXT=Testing/Functional ./bin/phpunit --configuration=Build/BuildEssentials/PhpUnit/FunctionalTests.xml -- DistributionPackages/Two13Tec.FlowShellCompletion/Tests/Functional)
  else
    echo "No functional tests defined for Two13Tec.FlowShellCompletion."
  fi

# Run all tests
test: test-unit test-func

# Check PHP syntax
lint-syntax:
  #!/usr/bin/env bash
  set -euo pipefail
  errors=$(find Classes/ Tests/ -name "*.php" -exec php -l {} \; | grep -v "No syntax errors" || true)
  if [ -n "$errors" ]; then
    echo "$errors"
    exit 1
  fi

# Check code style
lint-style:
  phpcs --standard=PSR12 Classes/ Tests/
  prettier --check ./composer.json

# Run static analysis
lint-static:
  ../../bin/phpstan analyse Classes/

# Check code format
lint-format:
  @treefmt --config-file .treefmt.toml --fail-on-change

# Fix code style
format:
  @treefmt --config-file .treefmt.toml

# Run all linting checks
lint: lint-format lint-syntax lint-style lint-static

# Full quality check (lint + test)
check: lint test
