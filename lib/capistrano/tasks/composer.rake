namespace :composer do

  desc "Running Composer Self-Update"
  task :update do
    on roles(:app), in: :sequence, wait: 5 do
      if test "[", "-e", "/usr/local/bin/composer", "]"
        execute :composer, "self-update"
      else
        execute :curl, "-sS", "https://getcomposer.org/installer", "|", :php
        as "root" do
          execute :mv, "composer.phar", "/usr/local/bin/composer"
        end
      end
    end
  end

  desc "Running Composer Install"
  task :install do
    on roles(:app), in: :sequence, wait: 5 do
      within release_path do
        execute :composer, "install --no-dev --no-scripts --optimize-autoloader"
      end
    end
  end

  desc "Running Composer Dump Autoload"
  task :install do
    on roles(:app), in: :sequence, wait: 5 do
      within release_path do
        execute :composer, "dump-autoload"
      end
    end
  end

end
