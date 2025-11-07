# Two13Tec.FlowShellCompletion

Shell completion support for Neos Flow CLI commands.

## Features

- **Multi-shell support**: zsh, fish, and nix/home-manager
- **Auto-detection**: Automatically detects your shell for installation
- **Command completion**: Tab-complete all Flow commands with descriptions
- **Easy installation**: One command to set up completion for your shell

## Installation

### Automatic Installation (Recommended)

The easiest way to install completion is to let the package auto-detect your shell:

```bash
./flow completion:install
```

This will:
1. Detect your current shell (zsh or fish)
2. Generate the appropriate completion script
3. Install it in the correct location
4. Update your shell configuration

### Manual Installation

#### zsh

Generate and install zsh completion:

```bash
./flow completion:install --shell=zsh
```

Or manually generate and place the completion file:

```bash
# Generate completion
./flow completion:generate --shell=zsh > flow-completion.zsh

# Add to your ~/.zshrc
echo 'source /path/to/project/flow-completion.zsh' >> ~/.zshrc

# Reload shell
source ~/.zshrc
```

#### fish

Generate and install fish completion:

```bash
./flow completion:install --shell=fish
```

Or manually:

```bash
# Generate completion
./flow completion:generate --shell=fish > ~/.config/fish/completions/flow.fish

# Fish will automatically load it on next shell start
```

#### nix / home-manager

For nix-based setups with home-manager, generate a nix module:

```bash
./flow completion:generate --shell=nix > flow-completion.nix
```

Then add it to your home-manager configuration:

**Option 1: Import as module**

```nix
# home.nix or flake.nix
{
  imports = [
    ./path/to/flow-completion.nix
  ];
}
```

**Option 2: Inline configuration**

For zsh:
```nix
programs.zsh.initExtra = ''
  source ${./flow-completion.zsh}
'';
```

For fish:
```nix
programs.fish.shellInit = ''
  source ${./flow-completion.fish}
'';
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

Generate shell completion script without installing:

```bash
./flow completion:generate --shell=zsh   # Generate zsh completion
./flow completion:generate --shell=fish  # Generate fish completion
./flow completion:generate --shell=nix   # Generate nix module
```

Output is sent to stdout, so you can redirect it to a file or pipe it elsewhere.

### completion:install

Install completion for your current shell:

```bash
./flow completion:install              # Auto-detect shell
./flow completion:install --shell=zsh  # Force zsh
./flow completion:install --shell=fish # Force fish
```

This command will:
- Generate the completion script
- Place it in the appropriate location
- Update your shell configuration file
- Provide instructions for activation

## Supported Shells

| Shell | Auto-install | Manual install | Nix module |
|-------|--------------|----------------|------------|
| zsh   | ✓            | ✓              | ✓          |
| fish  | ✓            | ✓              | ✓          |
| bash  | -            | -              | -          |

> **Note**: bash support may be added in future versions.

## Troubleshooting

### Completion not working after installation

**zsh**: Make sure you've reloaded your shell configuration:
```bash
source ~/.zshrc
```

**fish**: Restart your fish shell or run:
```bash
fish_update_completions
```

### Commands not showing up

If new commands aren't showing up in completion, regenerate the completion script:

```bash
./flow completion:install
```

This will update the completion with all currently available commands.

### Permission denied

If you get permission errors during installation, check that you have write access to:
- zsh: `~/.zshrc` and project directory
- fish: `~/.config/fish/completions/`

## Development

This package uses Flow's CommandManager to dynamically discover all available commands at generation time. This means:

- Completion is always up-to-date with your installed packages
- Custom commands from your packages are automatically included
- No manual maintenance required

### Extending

To add support for additional shells:

1. Add a new `generate{Shell}Completion()` method
2. Add the shell to the `generateCommand()` switch
3. Optionally add an `install{Shell}()` method for auto-installation

## License

This package is Open Source Software. For the full copyright and license information, please view the LICENSE file which was distributed with this source code.

## Credits

Developed by Two13Tec for the Neos Flow framework.
