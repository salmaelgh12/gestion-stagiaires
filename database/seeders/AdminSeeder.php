<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('nom_role', 'Admin')->first();

        Utilisateur::firstOrCreate(
            ['email' => 'admin@gestion-stagiaires.com'],
            [
                'nom' => '',
                'prenom' => 'Admin',
                'mot_de_passe' => Hash::make('Admin@2026'),
                'id_role' => $adminRole->id_role,
                'actif' => true,
            ]
        );
    }
}