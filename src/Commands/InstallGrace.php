<?php

namespace Hani221b\Grace\Commands;

use App\Providers\RouteServiceProvider;
use Hani221b\Grace\Support\Core;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Hani221b\Grace\Support\Str as GraceStr;

class InstallGrace extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grace:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Regitser grace route file in route service provider
     */

    public function register_route_file()
    {
        $body = Core::methodSource(RouteServiceProvider::class, 'boot');
        $routes_function = GraceStr::getBetween($body, "{", "}");
        $routes_function_array = explode("\n", $routes_function);
        $grace_route_registration_arrry = explode("\n", "
            Route::middleware('web')
                ->prefix('dashboard')
                ->group(base_path('routes/grace.php'));");
        foreach ($grace_route_registration_arrry as $template) {
            array_push($routes_function_array, $template);
        }

        $grace_route_registration_template = '';
        foreach ($routes_function_array as $index => $tem) {
            $grace_route_registration_template .= $routes_function_array[$index] . "\n";
        }
        $route_service_provider = \file_get_contents(base_path() . '/app/Providers/RouteServiceProvider.php');
        $route_service_provider = str_replace($routes_function, $grace_route_registration_template, $route_service_provider);
        file_put_contents(base_path() . '/app/Providers/RouteServiceProvider.php', $route_service_provider);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::connection()->getPdo();
            // publish stuff
            Artisan::call('vendor:publish', ['--tag' => 'grace']);
            $this->line('<fg=green> Publishing stuff:</>
        <fg=blue> Config files </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Config\grace.php <fg=yellow>]</> =><fg=yellow>[</>config\grace.php<fg=yellow>]</></>
        <fg=blue> Migration files </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Database\Migrations\2022_06_23_053830_create_languages_table.php<fg=yellow>]</>
        => <fg=yellow>[</>database\migrations\2022_06_23_053830_create_languages_table.php<fg=yellow>]</></>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Database\Migrations\2022_07_18_045909_create_tables_table.php<fg=yellow>]</>
        => <fg=yellow>[</>database\migrations\2022_07_18_045909_create_tables_table.php<fg=yellow>]</></>
        <fg=blue> Views files </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Views\Grace <fg=yellow>]</> =><fg=yellow>[</>resources\views\grace<fg=yellow>]</></>
        <fg=blue> Assets files </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Assets <fg=yellow>]</> =><fg=yellow>[</>public\grace<fg=yellow>]</></>
        <fg=blue> Models </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Models\Language.php <fg=yellow>]</> =><fg=yellow>[</>app\Models\Language.php<fg=yellow>]</></>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Models\Table.php <fg=yellow>]</> =><fg=yellow>[</>app\Models\Table.php<fg=yellow>]</></>
        <fg=blue> Routes </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Routes\grace.php <fg=yellow>]</> =><fg=yellow>[</>routes\grace.php<fg=yellow>]</></>
        <fg=blue> Seeders </>
        <fg=green><fg=yellow>[</>Hani221b\Grace\Database\Seeders\LanguageSeeder.php <fg=yellow>]</> =><fg=yellow>[</>database\seeders\LanguageSeeder.php<fg=yellow>]</></>
      ');
            //register route file
            $this->register_route_file();
            $this->line('<fg=blue>Adding route file registration to RouteServiceProvider</>
        ');
            // run migrate
            Artisan::call('migrate');
            $this->info("<fg=yellow>Migrating: </> <fg=white>2022_06_23_053830_create_languages_table.php</>");
            $this->info("<fg=green>Migrated: </> <fg=white>2022_06_23_053830_create_languages_table.php</>");
            // seeding languages
            Artisan::call('db:seed', ['--class' => 'LanguageSeeder']);
            $this->line('<fg=blue>Seeding Langauges</>
        ');
        } catch (\Exception $excecption) {
            $this->info($excecption);
            // $db = config('database.connections.mysql.database');
            // $this->info("<fg=red>Database $db was not found. Please create it before installing Grace</>");
        }

    }
}
