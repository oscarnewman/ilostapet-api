namespace :laravel do

  desc "Setup Laravel folder permissions"
  task :permissions do
    on roles(:app), in: :sequence, wait: 5 do
      within release_path do
        execute :chmod, "u+x artisan"
        execute :chmod, "-R 777 storage/"
        execute :chmod, "-R 777 bootstrap/"
      end
    end
  end

  desc "Optimize Laravel Class Loader"
  task :optimize do
    on roles(:app), in: :sequence, wait: 5 do
      within release_path do
        execute :php, "artisan clear-compiled"
        execute :php, "artisan optimize"
      end
    end
  end

  desc "Run Laravel Artisan migrate task."
  task :migrate do
    on roles(:app), in: :sequence, wait: 5 do
      within release_path do
        execute :php, "artisan down"
        execute :php, "artisan migrate --force"
        execute :php, "artisan up"
      end
    end
  end

  # desc "Update Laravel GeoIP Database"
  # task :geoip do
  #   on roles(:app), in: :sequence, wait: 5 do
  #     within release_path do
  #       execute :php, "artisan geoip:update"
  #     end
  #   end
  # end

end
