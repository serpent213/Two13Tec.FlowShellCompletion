<?php

declare(strict_types=1);

namespace Two13Tec\FlowShellCompletion\Tests\Functional;

/*
 * This file is part of the Two13Tec.FlowShellCompletion package.
 *
 * (c) Two13Tec
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Tests\FunctionalTestCase;

/**
 * Ensures that the generated zsh completion script can be sourced without errors.
 */
class ZshCompletionTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function generatedCompletionCanBeSourcedByZsh(): void
    {
        $zshBinary = $this->resolveZshBinary();
        if ($zshBinary === null) {
            $this->markTestSkipped('zsh binary not available in the test environment.');
        }

        $flowBinary = FLOW_PATH_ROOT . 'flow';
        self::assertFileExists($flowBinary, 'Flow root script missing.');

        $generateCommand = sprintf(
            'FLOW_CONTEXT=Testing/Functional %s completion:generate --shell=zsh',
            escapeshellarg($flowBinary)
        );

        $output = [];
        $exitCode = null;
        exec($generateCommand, $output, $exitCode);
        self::assertSame(0, $exitCode, 'Generating the zsh completion script must succeed.');

        $scriptContent = implode(PHP_EOL, $output) . PHP_EOL;
        self::assertNotSame('', trim($scriptContent), 'Completion script must not be empty.');

        $scriptPath = tempnam(sys_get_temp_dir(), 'flow_completion_');
        if ($scriptPath === false) {
            self::fail('Unable to create temporary file for the completion script.');
        }

        file_put_contents($scriptPath, $scriptContent);

        try {
            $checkCommand = sprintf(
                '%s -c %s',
                escapeshellarg($zshBinary),
                escapeshellarg(
                    sprintf('autoload -Uz compinit && compinit ' .
                        '&& source %s && typeset -f _flow >/dev/null', escapeshellarg($scriptPath))
                )
            );

            $zshOutput = [];
            $zshExitCode = null;
            $checkCommand .= ' 2>&1'; // Route stderr to stdout for full output
            exec($checkCommand, $zshOutput, $zshExitCode);

            self::assertSame(
                0,
                $zshExitCode,
                'zsh must be able to source the generated completion script: ' . implode(PHP_EOL, $zshOutput)
            );
        } finally {
            @unlink($scriptPath);
        }
    }

    private function resolveZshBinary(): ?string
    {
        $output = [];
        $exitCode = null;
        exec('command -v zsh', $output, $exitCode);

        if ($exitCode !== 0 || $output === []) {
            return null;
        }

        return trim($output[0]);
    }
}
