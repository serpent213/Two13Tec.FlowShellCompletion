# Two13Tec.FlowShellCompletion

Shell completion support for Neos Flow CLI commands.

## Features

- **zsh support**: Full completion support for zsh
- **Command completion**: Tab-complete all Flow commands with descriptions
- **Easy installation**: One command to set up completion for your shell

## Installation

### Quick Start (eval method)

The quickest way to enable completion for your current shell session:

```bash
eval "$(./flow completion:generate --shell=zsh)"
```

If you have an alias defined like

```bash
alias flow="./flow"
```

the completion will work as well for `flow`, besides `./flow`.

### Manual Installation

Generate the completion script and save it to a file:

```bash
./flow completion:generate --shell=zsh > flow-completion.zsh
```

Then add it to your `~/.zshrc`:

```bash
# Add to your ~/.zshrc
echo 'source /path/to/project/flow-completion.zsh' >> ~/.zshrc

# Reload shell
source ~/.zshrc
```

## Usage

Once installed, you can use tab completion with the `./flow` command:

```bash
./flow <TAB>                    # List all available commands
./flow cache:<TAB>              # List all cache commands
./flow doctrine:m<TAB>          # Complete doctrine:migrate, etc.
```

Each command will show a brief description when you tab-complete.

## Commands

### completion:generate

Generate shell completion script:

```bash
./flow completion:generate --shell=zsh
```

Output is sent to stdout, so you can redirect it to a file, pipe it elsewhere, or use it with eval.

### Extending

To add support for additional shells:

1. Add a new `generate{Shell}Completion()` method
2. Add the shell to the `generateCommand()` validation logic
3. Update the `renderZshHandler()` and related methods for shell-specific formatting

## License

This package is Open Source Software. For the full copyright and license information, please view the LICENSE file which was distributed with this source code.

## Credits

Developed by Steffen „213tec“ Beyer.
