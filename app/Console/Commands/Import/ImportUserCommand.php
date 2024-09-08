<?php

namespace App\Console\Commands\Import;

use App\CsvParser\FlorixErreichbarkeiten\Parser;
use App\CsvParser\FlorixErreichbarkeiten\Person;
use App\Models\Role;
use App\Models\RoleName;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;

use function Laravel\Prompts\error;
use function Laravel\Prompts\pause;
use function Laravel\Prompts\spin;

class ImportUserCommand extends Command
{
    protected $signature = 'import:user {filename : report.csv from Florix export Erreichbarkeiten}
                            {--ea : Einsatzabteilung} {--verein : Vereinsmitglied} {--em : Alters- und Ehrenmitglied}
                            {--no-create : Erstellt keine neuen Benutzer}';

    protected $description = 'Importiert ein Erreichbarkeiten CSV-Report als User der Anwendung.';

    protected \WeakMap $status;

    public function __construct()
    {
        parent::__construct();
        $this->status = new \WeakMap;
    }

    public function handle(Parser $parser): void
    {
        if (! $this->validateOptions()) {
            return;
        }

        $this->createAltScreen();

        /** @var Collection<Person> $people */
        $people = spin(fn () => $parser->parse($this->argument('filename')), 'Parsing CSV data...');

        $attachRole = $this->roleToAttach();

        $columnWidths = $this->calculateColumnWidths($people);

        foreach ($people as $index => $person) {

            try {
                // Create or fetch user
                $user = $this->getUser($person);

                // Attach role
                if ($user !== null && $attachRole) {
                    $user->roles()->attach($attachRole->id);
                }

                $this->status[$person] = match (true) {
                    $user === null => terminal_style('Nicht angelegt', 'blue'),
                    $user->wasRecentlyCreated => terminal_style('Neu angelegt', 'green'),
                    default => terminal_style('Bereits angelegt', 'green'),
                };

            } catch (\Exception $e) {
                $this->status[$person] = terminal_style('Fehler', 'red', null, 'bold');
                Log::error($e);
            }

            $length = 30;
            $offset = min(max(0, $index - ($length - 5)), count($people) - $length);
            $rows = $this->getShowableData($people)->slice($offset, $length);

            $this->clearScreen();
            (new Table($this->output))
                ->setColumnWidths($columnWidths)
                ->setHeaders(['Nachname', 'Vorname', 'E-Mail', 'Benutzername', 'Status'])
                ->setRows($rows->toArray())
                ->render();
        }

        pause('Drücke Enter zum fertigstellen...');
    }

    protected function validateOptions(): bool
    {
        $options = [
            'ea' => $this->option('ea'),
            'verein' => $this->option('verein'),
            'em' => $this->option('em'),
        ];

        if (array_sum($options) !== 1) {
            error('⚠️  Nur eine der Optionen --ea, --verein oder --em darf übergeben werden.');

            return false;
        }

        return true;
    }

    public function createAltScreen(): void
    {
        $this->line("\033[?1049h"); // Enable alternate screen buffer
    }

    protected function roleToAttach(): ?Role
    {
        if ($this->option('ea')) {
            return Role::byName(RoleName::Einsatzabteilung);
        } elseif ($this->option('verein')) {
            return Role::byName(RoleName::Vereinsmitglied);
        } elseif ($this->option('em')) {
            return Role::byName(RoleName::AltersUndEhrenabteilung);
        } else {
            return null;
        }
    }

    /**
     * @return array<int>
     */
    protected function calculateColumnWidths(Collection $people): array
    {
        $maxWidth = array_fill(0, 5, 0);
        $this->getShowableData($people)->map(function ($item) use (&$maxWidth) {
            foreach ($item as $index => $value) {
                $maxWidth[$index] = max($maxWidth[$index], mb_strwidth($value));
            }
        });

        return $maxWidth;
    }

    protected function getShowableData(Collection $people): Collection
    {
        return $people
            ->map(fn (Person $person) => [
                $person->lastName,
                $person->firstName,
                $person->email(),
                $this->username($person),
                $this->status[$person] ?? terminal_style('...', 'yellow'),
            ]);
    }

    protected function username(Person $person): string
    {
        [$username, $hostname] = explode('@', $person->email(), 2) + [null, null];

        if ($hostname === 'ff-frankfurt-hausen.de') {
            return mb_strtolower($username);
        }

        $lengthFirst = 0;

        do {
            $lengthFirst++;

            $username = Str::of($person->firstName)->lower()->substr(0, $lengthFirst)->append('.')
                .Str::of($person->lastName)
                    ->lower()
                    ->replace(['ä', 'ö', 'ü', 'ß'], ['ae', 'oe', 'ue', 'ss'])
                    ->replaceMatches(['/\s+/', '/[^\w\d]/'], '');

        } while (User::whereUsername($username)->whereNot('email', $person->email())->exists());

        return $username;
    }

    public function getUser(mixed $person): ?User
    {
        if ($person->email() === null) {
            return null;
        }

        if ($this->option('no-create')) {
            return User::whereEmail($person->email())->first();
        }

        return User::firstOrCreate([
            'email' => $person->email(),
        ], [
            'first_name' => $person->firstName,
            'last_name' => $person->lastName,
            'username' => $this->username($person),
            'password' => Hash::make(Str::random()),
        ]);
    }

    public function clearScreen(): void
    {
        $this->line("\033[2J"); // Clear the screen
        $this->line("\033[H");  // Move cursor to the top left corner
    }
}
