<?php

namespace Database\Seeders;

use App\Models\Weapon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeaponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weapons = collect([
            [
                "name" => "Bow and Arrows",
                "skill" => "Firearms (Bow)",
                "damage" => "1D6+half DB",
                "base_range" => "30 yards",
                "uses_per_round" => "1",
                "bullets_in_mag" => "1",
                "cost" => "$7",
                "malfunction" => "97",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Brass Knuckles",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D3+1+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$1",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Bullwhip",
                "skill" => "Fighting (Whip)",
                "damage" => "1D3+half",
                "base_range" => "DB 10 feet",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$5",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Burning Torch",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D6+burn",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "=",
                "cost" => "$0.05",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Blackjack (Cosh, life-preserver)",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D8+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$2",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Club, large (baseball, cricket bat, poker)",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D8+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$3",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Club,small (nightstick)",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D6+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$3",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Crossbow (i)",
                "skill" => "Firearms (Bow)",
                "damage" => "1D8+2",
                "base_range" => "50 yards",
                "uses_per_round" => "1/2",
                "bullets_in_mag" => "1",
                "cost" => "$10",
                "malfunction" => "96",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Garrote*(i)",
                "skill" => "Fighting (Garrote)",
                "damage" => "1D6+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$0.50",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Hatchet/Sickle (i)",
                "skill" => "Fighting (Axe)",
                "damage" => "1D6+1+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$3",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Knife, Large (machete, etc.) (i)",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D8+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$4",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Knife,Medium (carving knife, etc.) (i)",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D4+2+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$2",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Knife, Small (switchblade, etc.) (i)",
                "skill" => "Fighting (Brawl)",
                "damage" => "1D4+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$2",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Nunchaku",
                "skill" => "Fighting (Flail)",
                "damage" => "1D8+DB",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$1",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Rock, Thrown",
                "skill" => "Throw",
                "damage" => "1D4+half DB",
                "base_range" => "STR/ 5 yards",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "-",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Shuriken (i)",
                "skill" => "Throw",
                "damage" => "1D3+half DB",
                "base_range" => "STR/ 5 yards",
                "uses_per_round" => "2",
                "bullets_in_mag" => "One Use",
                "cost" => "$0.50",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Spear (cavalry lance)(i)",
                "skill" => "Fighting (Spear)",
                "damage" => "1D8+1",
                "base_range" => "Touch",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$25",
                "malfunction" => "-",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Spear, Thrown (i)",
                "skill" => "Throw",
                "damage" => "1D8+half DB",
                "base_range" => "STR/ 5 yards",
                "uses_per_round" => "1",
                "bullets_in_mag" => "-",
                "cost" => "$1",
                "malfunction" => "",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".22 Short Automatic",
                "skill" => "Firearms (handgun)",
                "damage" => "1D6",
                "base_range" => "10 yards",
                "uses_per_round" => "1 (3)",
                "bullets_in_mag" => "6",
                "cost" => "$25",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".25 Derringer (1B)",
                "skill" => "Firearms (handgun)",
                "damage" => "1D6",
                "base_range" => "3 yards",
                "uses_per_round" => "1",
                "bullets_in_mag" => "1",
                "cost" => "$12",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".32 or 7.65mm Revolver",
                "skill" => "Firearms (handgun)",
                "damage" => "1D8",
                "base_range" => "15 yards",
                "uses_per_round" => "1 (3)",
                "bullets_in_mag" => "6",
                "cost" => "$15",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".32 or 7.65mm Automatic",
                "skill" => "Firearms (handgun)",
                "damage" => "1D8",
                "base_range" => "15 yards",
                "uses_per_round" => "1 (3)",
                "bullets_in_mag" => "8",
                "cost" => "$20",
                "malfunction" => "99",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Model P08 Luger",
                "skill" => "Firearms (handgun)",
                "damage" => "1D10",
                "base_range" => "15 yards",
                "uses_per_round" => "1 (3)",
                "bullets_in_mag" => "8",
                "cost" => "$75",
                "malfunction" => "99",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".45 Revolver",
                "skill" => "Firearms (handgun)",
                "damage" => "1D10+2",
                "base_range" => "15 yards",
                "uses_per_round" => "1 (3)",
                "bullets_in_mag" => "6",
                "cost" => "$30",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".45 Automatic",
                "skill" => "Firearms (handgun)",
                "damage" => "1D10+2",
                "base_range" => "15 yards",
                "uses_per_round" => "1 (3)",
                "bullets_in_mag" => "7",
                "cost" => "$40",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "20-gauge Shotgun (2B)",
                "skill" => "Firearms (shotgun)",
                "damage" => "2D6/1D6/1D3",
                "base_range" => "10/20/50 yards",
                "uses_per_round" => "1 or 2",
                "bullets_in_mag" => "2",
                "cost" => "$35",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "16-gauge Shotgun (2B)",
                "skill" => "Firearms (shotgun)",
                "damage" => "2D6+2/1D6+1/1D4",
                "base_range" => "10/20/50 yards",
                "uses_per_round" => "1 or 2",
                "bullets_in_mag" => "2",
                "cost" => "$40",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "12-gauge Shotgun (2B)",
                "skill" => "Firearms (shotgun)",
                "damage" => "4D6/2D6/1D6",
                "base_range" => "10/20/50 yards",
                "uses_per_round" => "1 or 2",
                "bullets_in_mag" => "2",
                "cost" => "$40",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "12-gauge Shotgun (semi-auto)",
                "skill" => "Firearms (shotgun)",
                "damage" => "4D6/2D6/1D6",
                "base_range" => "10/20/50 yards",
                "uses_per_round" => "1 (2)",
                "bullets_in_mag" => "5",
                "cost" => "$45",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "12-gauge Shotgun (2B sawed off)",
                "skill" => "Firearms (shotgun)",
                "damage" => "4D6/1D6",
                "base_range" => "5/10 yards",
                "uses_per_round" => "1 or 2",
                "bullets_in_mag" => "2",
                "cost" => "N/A",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Bergmann MP181/MP2811",
                "skill" => "Firearms (SMG)",
                "damage" => "1D10",
                "base_range" => "20 yards",
                "uses_per_round" => "1 (2) or full auto",
                "bullets_in_mag" => "20/30/32",
                "cost" => "$1,000",
                "malfunction" => "96",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Thompson",
                "skill" => "Firearms (SMG)",
                "damage" => "1D10+2",
                "base_range" => "20 yards",
                "uses_per_round" => "1 or full auto",
                "bullets_in_mag" => "20/30/50",
                "cost" => "$200+",
                "malfunction" => "96",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Browning Auto",
                "skill" => "Rifle M1918",
                "damage" => "Firearms (MG) 2D6+4",
                "base_range" => "90 yards",
                "uses_per_round" => "1 (2) or full",
                "bullets_in_mag" => "auto",
                "cost" => "20 $800",
                "malfunction" => "100",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => ".30 Browning M1917A1",
                "skill" => "Firearms (MG)",
                "damage" => "2D6+4",
                "base_range" => "150 yards",
                "uses_per_round" => "Full auto",
                "bullets_in_mag" => "250",
                "cost" => "$3,000",
                "malfunction" => "96",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Bren Gun",
                "skill" => "Firearms (MG)",
                "damage" => "2D6+4",
                "base_range" => "110 yards",
                "uses_per_round" => "1 or full auto",
                "bullets_in_mag" => "30/100",
                "cost" => "$3,000",
                "malfunction" => "96",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Mark I Lewis Gun",
                "skill" => "Firearms (MG)",
                "damage" => "2D6+4",
                "base_range" => "110 yards",
                "uses_per_round" => "Full auto",
                "bullets_in_mag" => "47/97",
                "cost" => "$3,000",
                "malfunction" => "96",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ],
            [
                "name" => "Vickers .303",
                "skill" => "Machine Gun",
                "damage" => "Firearms (MG)",
                "base_range" => "2D6+4",
                "uses_per_round" => "110 yards",
                "bullets_in_mag" => "Full auto",
                "cost" => "250",
                "malfunction" => "N/A",
                "created_at" => "2024-05-05T08:17:30.000000Z",
                "updated_at" => "2024-05-05T08:17:30.000000Z"
            ]
        ]);

        $weapons->each(fn ($w) => Weapon::create($w));
    }
}
