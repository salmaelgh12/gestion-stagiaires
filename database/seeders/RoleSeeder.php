<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nom_role' => 'Admin', 'description' => 'Administrateur système'],
            ['nom_role' => 'Responsable de compétence', 'description' => 'Gère les stagiaires et affectations'],
            ['nom_role' => 'Encadrant', 'description' => 'Encadre les stagiaires au quotidien'],
            ['nom_role' => 'Stagiaire', 'description' => 'Utilisateur stagiaire'],
            ['nom_role' => 'RH', 'description' => 'Responsable RH'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom_role' => $role['nom_role']], $role);
        }
    }
}