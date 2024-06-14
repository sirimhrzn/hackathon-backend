{
  description = "Radio and Podcast";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs?ref=nixpkgs-unstable";
  };

  outputs =
    { self, nixpkgs }:
    let
      system = "x86_64-linux";
      pkgs = import nixpkgs { inherit system; };
      php = pkgs.php83.buildEnv {
        extensions = ({ enabled, all }: enabled ++ (with all; [ redis iconv mbstring openswoole ]));
        extraConfig = ''
          memory_limit=500M
        '';
      };
      composer = pkgs.php83Packages.composer;
      lsp = pkgs.php83Packages.intelephense;
      fmt = pkgs.php83Packages.php-cs-fixer;
    in
    {
      devShells.${system}.default = pkgs.mkShell {
        buildInputs = [
          php
          composer
          fmt
        ];
        shellHook = ''
            alias rfdb='php artisan db:wipe && php artisan migrate && php artisan db:seed'
        '';
      };
    };
}
