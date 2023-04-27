<?php

namespace App\Console\Commands;

use App\Role;
use Illuminate\Console\Command;

class UpdateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //[caption, icon_name, url]
        $moduls = [
            "dashboard" => [
                "caption" => "Asosiy",
                "icon" => "dashboard",
                "url" => "dashboard",
            ],
            "user" => [
                "caption" => "Foydalanuvchilar",
                "icon" => "people",
                "url" => "users",
            ]  ,
            "organizations" => [
                "caption" => "Tashkilotlar",
                "icon" => "dashboard",
                "url" => "organizations",
            ],
            "yuridik" => [
                "label" => "Yuridik bo'lim",
                "caption" => "Shartnomalar va talonlar",
                "icon" => "assessment",
                "children" => [
                    "shartnoma" => [
                        "caption" => "Shartnomalar",
                        "icon" => "receipt",
                        "url" => "shartnomalar",
                    ],
                    "talon" => [
                        "caption" => "Talonlar",
                        "icon" => "receipt",
                        "url" => "talonlar",
                    ],
                    "haydovchi" => [
                        "caption" => "Haydovchi",
                        "icon" => "engineering",
                        "url" => "haydovchi",
                    ],
                    "texnika" => [
                        "caption" => "Texnikalar",
                        "icon" => "local_shipping",
                        "url" => "texnika",
                    ],
                    "dalolatnoma" => [
                        "caption" => "Dalolatnoma",
                        "icon" => "receipt",
                        "url" => "dalolatnoma",
                    ],
                    "buyurtma" =>[
                        "caption" => "Buyurtma",
                        "icon" => "receipt",
                        "url" => "buyurtma",
                    ],
                ]
            ],
            "kadr" => [
                "caption" => "Kadrlar bo'limi",
                "icon" => "people",
                "url" => "kadrlar",
            ],
            "hisobot" => [
                "caption" => "Hisobotlar",
                "icon" => "dashboard",
                "url" => "hisobotlar",
            ],
            "shablon" => [
                "caption" => "Shablonlar",
                "icon" => "dashboard",
                "url" => "shablonlar",
            ],
              
        ];

        $all_permission = [
            "Admin" => [
                "dashboard" => [true, true, true, true],
                "user" => [true, true, true, false],
                "organizations" => [true, true, true, false],
                "yuridik" => [
                    "read" => true,
                    "children" => [
                        "shartnoma" => [true, true, true, false],
                        "haydovchi" => [true, true, true, false],
                        "texnika" => [true, true, true, false],
                        "talon" => [true, true, true, false],
                        "dalolatnoma" => [true, true, true, false],
                        "buyurtma" => [true, true, true, false],
                    ]
                ],
                "kadr" => [true, true, true, true],
                "hisobot" => [true, true, true, true],
                "shablon" => [true, true, true, true]
            ],
            "Admin_Tuman" => [
                "dashboard" => [true, true, true, true],
                "user" => [false, false, false, false],
                "organizations" => [false, false, false, false],
                "yuridik" => [
                    "read" => true,
                    "children" => [
                        "shartnoma" => [true, true, true, true],
                        "haydovchi" => [true, true, true, true],
                        "texnika" => [true, true, true, true],
                        "talon" => [true, true, true, true],
                        "dalolatnoma" => [true, true, true, true],
                        "buyurtma" => [true, true, true, true],
                    ]
                ],
                "kadr" => [true, true, true, true],
                "hisobot" => [true, true, true, true],
                "shablon" => [true, true, true, true]
            ],
            "Admin_Korxona" => [
                "dashboard" => [true, true, true, true],
                "user" => [false, false, false, false],
                "organizations" => [false, false, false, false],
                "yuridik" => [
                    "read" => true,
                    "children" => [
                        "shartnoma" => [true, true, true, true],
                        "haydovchi" => [true, true, true, true],
                        "texnika" => [true, true, true, true],
                        "talon" => [true, true, true, true],
                        "dalolatnoma" => [true, true, true, true],
                        "buyurtma" => [true, true, true, true],
                    ]
                ],
                "kadr" => [true, true, true, true],
                "hisobot" => [true, true, true, true],
                "shablon" => [true, true, true, true],
            ]
        ];

        foreach ($all_permission as $key => $value) {
            $role = Role::where("name", $key)->first();
            if (empty($role)) {
                $role = new Role();
                $role->name = $key;
            }
            $role->permission = json_encode(["data" => $value]);
            $role->save();
            print("$key\n");
        }
        return 0;
    }
}
