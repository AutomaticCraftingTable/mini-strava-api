{
  description = "API development dependencies";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = nixpkgs.legacyPackages.${system};
      in
      {
        devShells.default = pkgs.mkShell {
          buildInputs = with pkgs; [
            php82
            php82Packages.composer
            laravel
          ];
 
          shellHook = ''
            [ -n "$FLAKE_ENV" ] && exit
            export FLAKE_ENV=1

            PS1='\[\033[35m\][\[\033[34m\]nix-flake\[\033[35m\]]\[\033[0m\] \w \$ '
            export PATH="$PWD/vendor/bin:$PATH"
          '';
        };
      }
    );
}
