# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'ilostapet-api'
set :repo_url, 'git@github.com:oscarnewman/ilostapet-api.git'

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/var/www/api.ilostapet.net'

set :ssh_options, {
    keys: %w(~/.ssh/ilap_do), # Use our local SSH keys
    user: 'root'
}

set :use_sudo, true

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, fetch(:linked_files, []).push('.env', 'public/.htaccess')

# Default value for linked_dirs is []
# set :linked_dirs, fetch(:linked_dirs, []).push('log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'vendor/bundle', 'public/system')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5

namespace :deploy do

  after :updated, "composer:update" # composer namespace
  after :updated, "composer:install" # install task

  after :updated, "laravel:permissions"
  after :updated, "laravel:optimize"
  after :updated, "laravel:migrate"
  # after :updated, "laravel:geoip"

end
