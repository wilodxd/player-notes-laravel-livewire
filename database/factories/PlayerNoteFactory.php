<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\PlayerNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerNote>
 */
class PlayerNoteFactory extends Factory
{
    protected $model = PlayerNote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-60 days', 'now');

        return [
            'content' => fake()->randomElement([
                'Consultó sobre un retiro pendiente, se le explicó que falta verificación de documento.',
                'Jugador frecuente, tratar con prioridad en próximos contactos.',
                'Reportó un problema técnico durante la partida, se derivó al equipo correspondiente.',
                'Se comunicó por un cargo duplicado, quedó a la espera de respuesta.',
                'Solicitó información sobre límites de juego responsable, se le enviaron los enlaces correspondientes.',
                'Reincidente en reclamos sin fundamento, monitorear próximas interacciones.',
                'Reportó dificultades para acceder a su cuenta, se resolvió tras validar identidad.',
                'Consultó por una promoción vigente, se le explicaron los términos y condiciones.',
                'Se mostró disconforme con el tiempo de respuesta del soporte, se ofrecieron disculpas.',
                'Confirmó que el inconveniente reportado la sesión anterior quedó resuelto.',
            ]),
            'player_id' => Player::factory(),
            'user_id' => User::factory(),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
