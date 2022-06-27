<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleHasPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('role_has_permissions')->delete();

        \DB::table('role_has_permissions')->insert(array(
            0 => array (
                'permission_id' => 1,
                'role_id' => 1,
            ),
            1 => array (
                'permission_id' => 2,
                'role_id' => 1,
            ),
            2 => array (
                'permission_id' => 3,
                'role_id' => 1,
            ),
            3 => array (
                'permission_id' => 4,
                'role_id' => 1,
            ),
            4 => array (
                'permission_id' => 5,
                'role_id' => 1,
            ),
            5 => array (
                'permission_id' => 6,
                'role_id' => 1,
            ),
            6 => array (
                'permission_id' => 7,
                'role_id' => 1,
            ),
            7 => array (
                'permission_id' => 8,
                'role_id' => 1,
            ),
            8 => array (
                'permission_id' => 9,
                'role_id' => 1,
            ),
            9 => array (
                'permission_id' => 10,
                'role_id' => 1,
            ),
            10 => array (
                'permission_id' => 11,
                'role_id' => 1,
            ),
            11 => array (
                'permission_id' => 12,
                'role_id' => 1,
            ),
            12 => array (
                'permission_id' => 13,
                'role_id' => 1,
            ),
            13 => array (
                'permission_id' => 14,
                'role_id' => 1,
            ),
            14 => array (
                'permission_id' => 15,
                'role_id' => 1,
            ),
            15 => array (
                'permission_id' => 16,
                'role_id' => 1,
            ),
            16 => array (
                'permission_id' => 17,
                'role_id' => 1,
            ),
            17 => array (
                'permission_id' => 18,
                'role_id' => 1,
            ),
            18 => array (
                'permission_id' => 19,
                'role_id' => 1,
            ),
            19 => array (
                'permission_id' => 20,
                'role_id' => 1,
            ),
            20 => array (
                'permission_id' => 21,
                'role_id' => 1,
            ),
            21 => array (
                'permission_id' => 22,
                'role_id' => 1,
            ),
            22 => array (
                'permission_id' => 23,
                'role_id' => 1,
            ),
            23 => array (
                'permission_id' => 24,
                'role_id' => 1,
            ),
            24 => array (
                'permission_id' => 25,
                'role_id' => 1,
            ),
            25 => array (
                'permission_id' => 26,
                'role_id' => 1,
            ),
            26 => array (
                'permission_id' => 27,
                'role_id' => 1,
            ),
            27 => array (
                'permission_id' => 28,
                'role_id' => 1,
            ),
            28 => array (
                'permission_id' => 29,
                'role_id' => 1,
            ),
            29 => array (
                'permission_id' => 30,
                'role_id' => 1,
            ),
            30 => array (
                'permission_id' => 31,
                'role_id' => 1,
            ),
            31 => array (
                'permission_id' => 32,
                'role_id' => 1,
            ),
            32 => array (
                'permission_id' => 33,
                'role_id' => 1,
            ),
            33 => array (
                'permission_id' => 34,
                'role_id' => 1,
            ),
            34 => array (
                'permission_id' => 35,
                'role_id' => 1,
            ),
            35 => array (
                'permission_id' => 36,
                'role_id' => 1,
            ),
            36 => array (
                'permission_id' => 37,
                'role_id' => 1,
            ),
            37 => array (
                'permission_id' => 38,
                'role_id' => 1,
            ),
            38 => array (
                'permission_id' => 39,
                'role_id' => 1,
            ),
            39 => array (
                'permission_id' => 40,
                'role_id' => 1,
            ),
            40 => array (
                'permission_id' => 41,
                'role_id' => 1,
            ),
            41 => array (
                'permission_id' => 42,
                'role_id' => 1,
            ),
            42 => array (
                'permission_id' => 43,
                'role_id' => 1,
            ),
            43 => array (
                'permission_id' => 44,
                'role_id' => 1,
            ),
            44 => array (
                'permission_id' => 45,
                'role_id' => 1,
            ),
            45 => array (
                'permission_id' => 46,
                'role_id' => 1,
            ),
            46 => array (
                'permission_id' => 47,
                'role_id' => 1,
            ),
            47 => array (
                'permission_id' => 48,
                'role_id' => 1,
            ),
            48 => array (
                'permission_id' => 49,
                'role_id' => 1,
            ),
            49 => array (
                'permission_id' => 50,
                'role_id' => 1,
            ),
            50 => array (
                'permission_id' => 51,
                'role_id' => 1,
            ),
            51 => array (
                'permission_id' => 52,
                'role_id' => 1,
            ),
            52 => array (
                'permission_id' => 53,
                'role_id' => 1,
            ),
            53 => array (
                'permission_id' => 54,
                'role_id' => 1,
            ),
            54 => array (
                'permission_id' => 55,
                'role_id' => 1,
            ),
            55 => array (
                'permission_id' => 56,
                'role_id' => 1,
            ),
            56 => array (
                'permission_id' => 57,
                'role_id' => 1,
            ),
            57 => array (
                'permission_id' => 58,
                'role_id' => 1,
            ),
            58 => array (
                'permission_id' => 59,
                'role_id' => 1,
            ),
            59 => array (
                'permission_id' => 60,
                'role_id' => 1,
            ),
            60 => array (
                'permission_id' => 61,
                'role_id' => 1,
            ),
            61 => array (
                'permission_id' => 62,
                'role_id' => 1,
            ),
            62 => array (
                'permission_id' => 63,
                'role_id' => 1,
            ),
            63 => array (
                'permission_id' => 64,
                'role_id' => 1,
            ),
            64 => array (
                'permission_id' => 65,
                'role_id' => 1,
            ),
            65 => array (
                'permission_id' => 66,
                'role_id' => 1,
            ),
            66 => array (
                'permission_id' => 67,
                'role_id' => 1,
            ),
            67 => array (
                'permission_id' => 68,
                'role_id' => 1,
            ),
            68 => array (
                'permission_id' => 69,
                'role_id' => 1,
            ),
            69 => array (
                'permission_id' => 76,
                'role_id' => 1,
            ),
            70 => array (
                'permission_id' => 77,
                'role_id' => 1,
            ),
            71 => array (
                'permission_id' => 78,
                'role_id' => 1,
            ),
            72 => array (
                'permission_id' => 79,
                'role_id' => 1,
            ),
            73 => array (
                'permission_id' => 80,
                'role_id' => 1,
            ),
            74 => array (
                'permission_id' => 81,
                'role_id' => 1,
            ),
            75 => array (
                'permission_id' => 82,
                'role_id' => 1,
            ),
            76 => array (
                'permission_id' => 83,
                'role_id' => 1,
            ),
            77 => array (
                'permission_id' => 84,
                'role_id' => 1,
            ),
            78 => array (
                'permission_id' => 85,
                'role_id' => 1,
            ),
            79 => array (
                'permission_id' => 86,
                'role_id' => 1,
            ),
            80 => array (
                'permission_id' => 87,
                'role_id' => 1,
            ),
            81 => array (
                'permission_id' => 88,
                'role_id' => 1,
            ),
            82 => array (
                'permission_id' => 89,
                'role_id' => 1,
            ),
            83 => array (
                'permission_id' => 90,
                'role_id' => 1,
            ),
            84 => array (
                'permission_id' => 91,
                'role_id' => 1,
            ),
            85 => array (
                'permission_id' => 92,
                'role_id' => 1,
            ),
            86 => array (
                'permission_id' => 93,
                'role_id' => 1,
            ),
            87 => array (
                'permission_id' => 94,
                'role_id' => 1,
            ),
            88 => array (
                'permission_id' => 95,
                'role_id' => 1,
            ),
            89 => array (
                'permission_id' => 96,
                'role_id' => 1,
            ),
            90 => array (
                'permission_id' => 97,
                'role_id' => 1,
            ),
            91 => array (
                'permission_id' => 98,
                'role_id' => 1,
            ),
            92 => array (
                'permission_id' => 99,
                'role_id' => 1,
            ),
            93 => array (
                'permission_id' => 100,
                'role_id' => 1,
            ),
            94 => array (
                'permission_id' => 101,
                'role_id' => 1,
            ),
            95 => array (
                'permission_id' => 102,
                'role_id' => 1,
            ),
            96 => array (
                'permission_id' => 103,
                'role_id' => 1,
            ),
            97 => array (
                'permission_id' => 104,
                'role_id' => 1,
            ),
            98 => array (
                'permission_id' => 105,
                'role_id' => 1,
            ),
            99 => array (
                'permission_id' => 106,
                'role_id' => 1,
            ),
            100 => array (
                'permission_id' => 107,
                'role_id' => 1,
            ),
            101 => array (
                'permission_id' => 108,
                'role_id' => 1,
            ),
            102 => array (
                'permission_id' => 109,
                'role_id' => 1,
            ),
            103 => array (
                'permission_id' => 110,
                'role_id' => 1,
            ),
            104 => array (
                'permission_id' => 111,
                'role_id' => 1,
            ),
            105 => array (
                'permission_id' => 112,
                'role_id' => 1,
            ),
            106 => array (
                'permission_id' => 113,
                'role_id' => 1,
            ),
            107 => array (
                'permission_id' => 114,
                'role_id' => 1,
            ),
            108 => array (
                'permission_id' => 115,
                'role_id' => 1,
            ),
            109 => array (
                'permission_id' => 116,
                'role_id' => 1,
            ),
            110 => array (
                'permission_id' => 117,
                'role_id' => 1,
            ),
            111 => array (
                'permission_id' => 118,
                'role_id' => 1,
            ),
            112 => array (
                'permission_id' => 119,
                'role_id' => 1,
            ),
            113 => array (
                'permission_id' => 120,
                'role_id' => 1,
            ),
            114 => array (
                'permission_id' => 121,
                'role_id' => 1,
            ),
            115 => array (
                'permission_id' => 122,
                'role_id' => 1,
            ),
            116 => array (
                'permission_id' => 123,
                'role_id' => 1,
            ),
            117 => array (
                'permission_id' => 124,
                'role_id' => 1,
            ),
            118 => array (
                'permission_id' => 125,
                'role_id' => 1,
            ),
            119 => array (
                'permission_id' => 126,
                'role_id' => 1,
            ),
            120 => array (
                'permission_id' => 127,
                'role_id' => 1,
            ),
            121 => array (
                'permission_id' => 128,
                'role_id' => 1,
            ),
            122 => array (
                'permission_id' => 129,
                'role_id' => 1,
            ),
            123 => array (
                'permission_id' => 130,
                'role_id' => 1,
            ),
            124 => array (
                'permission_id' => 131,
                'role_id' => 1,
            ),
            125 => array (
                'permission_id' => 132,
                'role_id' => 1,
            ),
            126 => array (
                'permission_id' => 133,
                'role_id' => 1,
            ),
            127 => array (
                'permission_id' => 134,
                'role_id' => 1,
            ),
            128 => array (
                'permission_id' => 135,
                'role_id' => 1,
            ),
            129 => array (
                'permission_id' => 136,
                'role_id' => 1,
            ),
            130 => array (
                'permission_id' => 137,
                'role_id' => 1,
            ),
            131 => array (
                'permission_id' => 138,
                'role_id' => 1,
            ),
            132 => array (
                'permission_id' => 139,
                'role_id' => 1,
            ),
            133 => array (
                'permission_id' => 140,
                'role_id' => 1,
            ),
            134 => array (
                'permission_id' => 141,
                'role_id' => 1,
            ),
            135 => array (
                'permission_id' => 142,
                'role_id' => 1,
            ),
            136 => array (
                'permission_id' => 143,
                'role_id' => 1,
            ),
            137 => array (
                'permission_id' => 144,
                'role_id' => 1,
            ),
            138 => array (
                'permission_id' => 145,
                'role_id' => 1,
            ),
            139 => array (
                'permission_id' => 146,
                'role_id' => 1,
            ),
            140 => array (
                'permission_id' => 147,
                'role_id' => 1,
            ),
            141 => array (
                'permission_id' => 148,
                'role_id' => 1,
            ),
            142 => array (
                'permission_id' => 149,
                'role_id' => 1,
            ),
            143 => array (
                'permission_id' => 150,
                'role_id' => 1,
            ),
            144 => array (
                'permission_id' => 151,
                'role_id' => 1,
            ),
            145 => array (
                'permission_id' => 152,
                'role_id' => 1,
            ),
            146 => array (
                'permission_id' => 153,
                'role_id' => 1,
            ),
            147 => array (
                'permission_id' => 154,
                'role_id' => 1,
            ),
            148 => array (
                'permission_id' => 155,
                'role_id' => 1,
            ),
            149 => array (
                'permission_id' => 156,
                'role_id' => 1,
            ),
            150 => array (
                'permission_id' => 157,
                'role_id' => 1,
            ),
            151 => array (
                'permission_id' => 158,
                'role_id' => 1,
            ),
            152 => array (
                'permission_id' => 159,
                'role_id' => 1,
            ),
            153 => array (
                'permission_id' => 160,
                'role_id' => 1,
            ),
            154 => array (
                'permission_id' => 161,
                'role_id' => 1,
            ),
            155 => array (
                'permission_id' => 162,
                'role_id' => 1,
            ),
            156 => array (
                'permission_id' => 163,
                'role_id' => 1,
            ),
            157 => array (
                'permission_id' => 164,
                'role_id' => 1,
            ),
            158 => array (
                'permission_id' => 165,
                'role_id' => 1,
            ),
            159 => array (
                'permission_id' => 166,
                'role_id' => 1,
            ),
            160 => array (
                'permission_id' => 167,
                'role_id' => 1,
            ),
            161 => array (
                'permission_id' => 168,
                'role_id' => 1,
            ),
            162 => array (
                'permission_id' => 169,
                'role_id' => 1,
            ),
            163 => array (
                'permission_id' => 170,
                'role_id' => 1,
            ),
            164 => array (
                'permission_id' => 171,
                'role_id' => 1,
            ),
            165 => array (
                'permission_id' => 172,
                'role_id' => 1,
            ),
            166 => array (
                'permission_id' => 173,
                'role_id' => 1,
            ),
            167 => array (
                'permission_id' => 174,
                'role_id' => 1,
            ),
            168 => array (
                'permission_id' => 175,
                'role_id' => 1,
            ),
            169 => array (
                'permission_id' => 176,
                'role_id' => 1,
            ),
            170 => array (
                'permission_id' => 183,
                'role_id' => 1,
            ),
            171 => array (
                'permission_id' => 184,
                'role_id' => 1,
            ),
            172 => array (
                'permission_id' => 185,
                'role_id' => 1,
            ),
            173 => array (
                'permission_id' => 186,
                'role_id' => 1,
            ),
            174 => array (
                'permission_id' => 187,
                'role_id' => 1,
            ),
            175 => array (
                'permission_id' => 188,
                'role_id' => 1,
            ),
            176 => array (
                'permission_id' => 189,
                'role_id' => 1,
            ),
            177 => array (
                'permission_id' => 190,
                'role_id' => 1,
            ),
            178 => array (
                'permission_id' => 191,
                'role_id' => 1,
            ),
            179 => array (
                'permission_id' => 192,
                'role_id' => 1,
            ),
            180 => array (
                'permission_id' => 193,
                'role_id' => 1,
            ),
            181 => array (
                'permission_id' => 194,
                'role_id' => 1,
            ),
            182 => array (
                'permission_id' => 195,
                'role_id' => 1,
            ),
            183 => array (
                'permission_id' => 196,
                'role_id' => 1,
            ),
            184 => array (
                'permission_id' => 197,
                'role_id' => 1,
            ),
            185 => array (
                'permission_id' => 198,
                'role_id' => 1,
            ),
            186 => array (
                'permission_id' => 199,
                'role_id' => 1,
            ),
            187 => array (
                'permission_id' => 200,
                'role_id' => 1,
            ),
            188 => array (
                'permission_id' => 201,
                'role_id' => 1,
            ),
            189 => array (
                'permission_id' => 202,
                'role_id' => 1,
            ),
            190 => array (
                'permission_id' => 203,
                'role_id' => 1,
            ),
            191 => array (
                'permission_id' => 204,
                'role_id' => 1,
            ),
            192 => array (
                'permission_id' => 205,
                'role_id' => 1,
            ),
            193 => array (
                'permission_id' => 206,
                'role_id' => 1,
            ),
            194 => array (
                'permission_id' => 207,
                'role_id' => 1,
            ),
            195 => array (
                'permission_id' => 208,
                'role_id' => 1,
            ),
            196 => array (
                'permission_id' => 209,
                'role_id' => 1,
            ),
            197 => array (
                'permission_id' => 210,
                'role_id' => 1,
            ),
            198 => array (
                'permission_id' => 211,
                'role_id' => 1,
            ),
            199 => array (
                'permission_id' => 212,
                'role_id' => 1,
            ),
            200 => array (
                'permission_id' => 213,
                'role_id' => 1,
            ),
            201 => array (
                'permission_id' => 214,
                'role_id' => 1,
            ),
            202 => array (
                'permission_id' => 215,
                'role_id' => 1,
            ),
            203 => array (
                'permission_id' => 216,
                'role_id' => 1,
            ),
            204 => array (
                'permission_id' => 217,
                'role_id' => 1,
            ),
            205 => array (
                'permission_id' => 218,
                'role_id' => 1,
            ),
            206 => array (
                'permission_id' => 219,
                'role_id' => 1,
            ),
            207 => array (
                'permission_id' => 220,
                'role_id' => 1,
            ),
            208 => array (
                'permission_id' => 222,
                'role_id' => 1,
            ),
            209 => array (
                'permission_id' => 223,
                'role_id' => 1,
            ),
            210 => array (
                'permission_id' => 224,
                'role_id' => 1,
            ),
            211 => array (
                'permission_id' => 225,
                'role_id' => 1,
            ),
            212 => array (
                'permission_id' => 226,
                'role_id' => 1,
            ),
            213 => array (
                'permission_id' => 227,
                'role_id' => 1,
            ),
            214 => array (
                'permission_id' => 228,
                'role_id' => 1,
            ),
            215 => array (
                'permission_id' => 229,
                'role_id' => 1,
            ),
            216 => array (
                'permission_id' => 230,
                'role_id' => 1,
            ),
            217 => array (
                'permission_id' => 231,
                'role_id' => 1,
            ),
            218 => array (
                'permission_id' => 232,
                'role_id' => 1,
            ),
            219 => array (
                'permission_id' => 233,
                'role_id' => 1,
            ),
            220 => array (
                'permission_id' => 234,
                'role_id' => 1,
            ),
            221 => array (
                'permission_id' => 235,
                'role_id' => 1,
            ),
            222 => array (
                'permission_id' => 236,
                'role_id' => 1,
            ),
            223 => array (
                'permission_id' => 237,
                'role_id' => 1,
            ),
            224 => array (
                'permission_id' => 238,
                'role_id' => 1,
            ),
            225 => array (
                'permission_id' => 239,
                'role_id' => 1,
            ),
            226 => array (
                'permission_id' => 240,
                'role_id' => 1,
            ),
            227 => array (
                'permission_id' => 241,
                'role_id' => 1,
            ),
            228 => array (
                'permission_id' => 242,
                'role_id' => 1,
            ),
            229 => array (
                'permission_id' => 243,
                'role_id' => 1,
            ),
            230 => array (
                'permission_id' => 244,
                'role_id' => 1,
            ),
            231 => array (
                'permission_id' => 245,
                'role_id' => 1,
            ),
            232 => array (
                'permission_id' => 246,
                'role_id' => 1,
            ),
            233 => array (
                'permission_id' => 247,
                'role_id' => 1,
            ),
            234 => array (
                'permission_id' => 248,
                'role_id' => 1,
            ),
            235 => array (
                'permission_id' => 249,
                'role_id' => 1,
            ),
            236 => array (
                'permission_id' => 250,
                'role_id' => 1,
            ),
            237 => array (
                'permission_id' => 251,
                'role_id' => 1,
            ),
            238 => array (
                'permission_id' => 252,
                'role_id' => 1,
            ),
            239 => array (
                'permission_id' => 253,
                'role_id' => 1,
            ),
            240 => array (
                'permission_id' => 254,
                'role_id' => 1,
            ),
            241 => array (
                'permission_id' => 255,
                'role_id' => 1,
            ),
            242 => array (
                'permission_id' => 256,
                'role_id' => 1,
            ),
            243 => array (
                'permission_id' => 257,
                'role_id' => 1,
            ),
            244 => array (
                'permission_id' => 258,
                'role_id' => 1,
            ),
            245 => array (
                'permission_id' => 259,
                'role_id' => 1,
            ),
            246 => array (
                'permission_id' => 260,
                'role_id' => 1,
            ),
            247 => array (
                'permission_id' => 261,
                'role_id' => 1,
            ),
            248 => array (
                'permission_id' => 262,
                'role_id' => 1,
            ),
            249 => array (
                'permission_id' => 263,
                'role_id' => 1,
            ),
            250 => array (
                'permission_id' => 264,
                'role_id' => 1,
            ),
            251 => array (
                'permission_id' => 265,
                'role_id' => 1,
            ),
            252 => array (
                'permission_id' => 266,
                'role_id' => 1,
            ),
            253 => array (
                'permission_id' => 267,
                'role_id' => 1,
            ),
            254 => array (
                'permission_id' => 268,
                'role_id' => 1,
            ),
            255 => array (
                'permission_id' => 269,
                'role_id' => 1,
            ),
            256 => array (
                'permission_id' => 270,
                'role_id' => 1,
            ),
            257 => array (
                'permission_id' => 271,
                'role_id' => 1,
            ),
            258 => array (
                'permission_id' => 272,
                'role_id' => 1,
            ),
            259 => array (
                'permission_id' => 273,
                'role_id' => 1,
            ),
            260 => array (
                'permission_id' => 274,
                'role_id' => 1,
            ),
            261 => array (
                'permission_id' => 275,
                'role_id' => 1,
            ),
            262 => array (
                'permission_id' => 276,
                'role_id' => 1,
            ),
            263 => array (
                'permission_id' => 277,
                'role_id' => 1,
            ),
            264 => array (
                'permission_id' => 278,
                'role_id' => 1,
            ),
            265 => array (
                'permission_id' => 279,
                'role_id' => 1,
            ),
            266 => array (
                'permission_id' => 280,
                'role_id' => 1,
            ),
            267 => array (
                'permission_id' => 281,
                'role_id' => 1,
            ),
            268 => array (
                'permission_id' => 282,
                'role_id' => 1,
            ),
            269 => array (
                'permission_id' => 283,
                'role_id' => 1,
            ),
            270 => array (
                'permission_id' => 284,
                'role_id' => 1,
            ),
            271 => array (
                'permission_id' => 285,
                'role_id' => 1,
            ),
            272 => array (
                'permission_id' => 286,
                'role_id' => 1,
            ),
            273 => array (
                'permission_id' => 287,
                'role_id' => 1,
            ),
            274 => array (
                'permission_id' => 288,
                'role_id' => 1,
            ),
            275 => array (
                'permission_id' => 289,
                'role_id' => 1,
            ),
            276 => array (
                'permission_id' => 290,
                'role_id' => 1,
            ),
            277 => array (
                'permission_id' => 291,
                'role_id' => 1,
            ),
            278 => array (
                'permission_id' => 292,
                'role_id' => 1,
            ),
            279 => array (
                'permission_id' => 293,
                'role_id' => 1,
            ),
            280 => array (
                'permission_id' => 294,
                'role_id' => 1,
            ),
            281 => array (
                'permission_id' => 295,
                'role_id' => 1,
            ),
            282 => array (
                'permission_id' => 296,
                'role_id' => 1,
            ),
            283 => array (
                'permission_id' => 297,
                'role_id' => 1,
            ),
            284 => array (
                'permission_id' => 298,
                'role_id' => 1,
            ),
            285 => array (
                'permission_id' => 299,
                'role_id' => 1,
            ),
            286 => array (
                'permission_id' => 300,
                'role_id' => 1,
            ),
            287 => array (
                'permission_id' => 301,
                'role_id' => 1,
            ),
            288 => array (
                'permission_id' => 302,
                'role_id' => 1,
            ),
            289 => array (
                'permission_id' => 303,
                'role_id' => 1,
            ),
            290 => array (
                'permission_id' => 304,
                'role_id' => 1,
            ),
            291 => array (
                'permission_id' => 305,
                'role_id' => 1,
            ),
            292 => array (
                'permission_id' => 306,
                'role_id' => 1,
            ),
            293 => array (
                'permission_id' => 307,
                'role_id' => 1,
            ),
            294 => array (
                'permission_id' => 308,
                'role_id' => 1,
            ),
            295 => array (
                'permission_id' => 309,
                'role_id' => 1,
            ),
            296 => array (
                'permission_id' => 310,
                'role_id' => 1,
            ),
            297 => array (
                'permission_id' => 311,
                'role_id' => 1,
            ),
            298 => array (
                'permission_id' => 312,
                'role_id' => 1,
            ),
            299 => array (
                'permission_id' => 313,
                'role_id' => 1,
            ),
            300 => array (
                'permission_id' => 314,
                'role_id' => 1,
            ),
            301 => array (
                'permission_id' => 315,
                'role_id' => 1,
            ),
            302 => array (
                'permission_id' => 316,
                'role_id' => 1,
            ),
            303 => array (
                'permission_id' => 317,
                'role_id' => 1,
            ),
            304 => array (
                'permission_id' => 318,
                'role_id' => 1,
            ),
            305 => array (
                'permission_id' => 319,
                'role_id' => 1,
            ),
            306 => array (
                'permission_id' => 320,
                'role_id' => 1,
            ),
            307 => array (
                'permission_id' => 321,
                'role_id' => 1,
            ),
            308 => array (
                'permission_id' => 322,
                'role_id' => 1,
            ),
            309 => array (
                'permission_id' => 323,
                'role_id' => 1,
            ),
            310 => array (
                'permission_id' => 324,
                'role_id' => 1,
            ),
            311 => array (
                'permission_id' => 325,
                'role_id' => 1,
            ),
            312 => array (
                'permission_id' => 326,
                'role_id' => 1,
            ),
            313 => array (
                'permission_id' => 327,
                'role_id' => 1,
            ),
            314 => array (
                'permission_id' => 328,
                'role_id' => 1,
            ),
            315 => array (
                'permission_id' => 329,
                'role_id' => 1,
            ),
            316 => array (
                'permission_id' => 330,
                'role_id' => 1,
            ),
            317 => array (
                'permission_id' => 331,
                'role_id' => 1,
            ),
            318 => array (
                'permission_id' => 332,
                'role_id' => 1,
            ),
            319 => array (
                'permission_id' => 333,
                'role_id' => 1,
            ),
            320 => array (
                'permission_id' => 334,
                'role_id' => 1,
            ),
            321 => array (
                'permission_id' => 335,
                'role_id' => 1,
            ),
            322 => array (
                'permission_id' => 336,
                'role_id' => 1,
            ),
            323 => array (
                'permission_id' => 337,
                'role_id' => 1,
            ),
            324 => array (
                'permission_id' => 338,
                'role_id' => 1,
            ),
            325 => array (
                'permission_id' => 339,
                'role_id' => 1,
            ),
            326 => array (
                'permission_id' => 340,
                'role_id' => 1,
            ),
            327 => array (
                'permission_id' => 341,
                'role_id' => 1,
            ),
            328 => array (
                'permission_id' => 342,
                'role_id' => 1,
            ),
            329 => array (
                'permission_id' => 343,
                'role_id' => 1,
            ),
            330 => array (
                'permission_id' => 344,
                'role_id' => 1,
            ),
            331 => array (
                'permission_id' => 345,
                'role_id' => 1,
            ),
            332 => array (
                'permission_id' => 346,
                'role_id' => 1,
            ),
            333 => array (
                'permission_id' => 347,
                'role_id' => 1,
            ),
            334 => array (
                'permission_id' => 348,
                'role_id' => 1,
            ),
            335 => array (
                'permission_id' => 349,
                'role_id' => 1,
            ),
            336 => array (
                'permission_id' => 350,
                'role_id' => 1,
            ),
            337 => array (
                'permission_id' => 351,
                'role_id' => 1,
            ),
            338 => array (
                'permission_id' => 352,
                'role_id' => 1,
            ),
            339 => array (
                'permission_id' => 353,
                'role_id' => 1,
            ),
            340 => array (
                'permission_id' => 354,
                'role_id' => 1,
            ),
            341 => array (
                'permission_id' => 355,
                'role_id' => 1,
            ),
            342 => array (
                'permission_id' => 356,
                'role_id' => 1,
            ),
            343 => array (
                'permission_id' => 357,
                'role_id' => 1,
            ),
            344 => array (
                'permission_id' => 358,
                'role_id' => 1,
            ),
            345 => array (
                'permission_id' => 359,
                'role_id' => 1,
            ),
            346 => array (
                'permission_id' => 360,
                'role_id' => 1,
            ),
            347 => array (
                'permission_id' => 361,
                'role_id' => 1,
            ),
            348 => array (
                'permission_id' => 362,
                'role_id' => 1,
            ),
            349 => array (
                'permission_id' => 363,
                'role_id' => 1,
            ),
            350 => array (
                'permission_id' => 364,
                'role_id' => 1,
            ),
            351 => array (
                'permission_id' => 365,
                'role_id' => 1,
            ),
            352 => array (
                'permission_id' => 366,
                'role_id' => 1,
            ),
            353 => array (
                'permission_id' => 367,
                'role_id' => 1,
            ),
            354 => array (
                'permission_id' => 368,
                'role_id' => 1,
            ),
            355 => array (
                'permission_id' => 369,
                'role_id' => 1,
            ),
            356 => array (
                'permission_id' => 370,
                'role_id' => 1,
            ),
            357 => array (
                'permission_id' => 371,
                'role_id' => 1,
            ),
            358 => array (
                'permission_id' => 373,
                'role_id' => 1,
            ),
            359 => array (
                'permission_id' => 374,
                'role_id' => 1,
            ),
            360 => array (
                'permission_id' => 375,
                'role_id' => 1,
            ),
            361 => array (
                'permission_id' => 376,
                'role_id' => 1,
            ),
            362 => array (
                'permission_id' => 377,
                'role_id' => 1,
            ),
            363 => array (
                'permission_id' => 378,
                'role_id' => 1,
            ),
            364 => array (
                'permission_id' => 379,
                'role_id' => 1,
            ),
            365 => array (
                'permission_id' => 380,
                'role_id' => 1,
            ),
            366 => array (
                'permission_id' => 382,
                'role_id' => 1,
            ),
            367 => array (
                'permission_id' => 383,
                'role_id' => 1,
            ),
            368 => array (
                'permission_id' => 384,
                'role_id' => 1,
            ),
            369 => array (
                'permission_id' => 385,
                'role_id' => 1,
            ),
            370 => array (
                'permission_id' => 386,
                'role_id' => 1,
            ),
            371 => array (
                'permission_id' => 387,
                'role_id' => 1,
            ),
            372 => array (
                'permission_id' => 388,
                'role_id' => 1,
            ),
            373 => array (
                'permission_id' => 389,
                'role_id' => 1,
            ),
            374 => array (
                'permission_id' => 390,
                'role_id' => 1,
            ),
            375 => array (
                'permission_id' => 391,
                'role_id' => 1,
            ),
            376 => array (
                'permission_id' => 392,
                'role_id' => 1,
            ),
            377 => array (
                'permission_id' => 394,
                'role_id' => 1,
            ),
            378 => array (
                'permission_id' => 395,
                'role_id' => 1,
            ),
            379 => array (
                'permission_id' => 396,
                'role_id' => 1,
            ),
            380 => array (
                'permission_id' => 397,
                'role_id' => 1,
            ),
            381 => array (
                'permission_id' => 398,
                'role_id' => 1,
            ),
            382 => array (
                'permission_id' => 399,
                'role_id' => 1,
            ),
            383 => array (
                'permission_id' => 401,
                'role_id' => 1,
            ),
            384 => array (
                'permission_id' => 403,
                'role_id' => 1,
            ),
            385 => array (
                'permission_id' => 404,
                'role_id' => 1,
            ),
            386 => array (
                'permission_id' => 405,
                'role_id' => 1,
            ),
            387 => array (
                'permission_id' => 406,
                'role_id' => 1,
            ),
            388 => array (
                'permission_id' => 407,
                'role_id' => 1,
            ),
            389 => array (
                'permission_id' => 409,
                'role_id' => 1,
            ),
            390 => array (
                'permission_id' => 410,
                'role_id' => 1,
            ),
            391 => array (
                'permission_id' => 411,
                'role_id' => 1,
            ),
            392 => array (
                'permission_id' => 412,
                'role_id' => 1,
            ),
            393 => array (
                'permission_id' => 414,
                'role_id' => 1,
            ),
            394 => array (
                'permission_id' => 416,
                'role_id' => 1,
            ),
            395 => array (
                'permission_id' => 417,
                'role_id' => 1,
            ),
            396 => array (
                'permission_id' => 418,
                'role_id' => 1,
            ),
            397 => array (
                'permission_id' => 419,
                'role_id' => 1,
            ),
            398 => array (
                'permission_id' => 420,
                'role_id' => 1,
            ),
            399 => array (
                'permission_id' => 421,
                'role_id' => 1,
            ),
            400 => array (
                'permission_id' => 422,
                'role_id' => 1,
            ),
            401 => array (
                'permission_id' => 423,
                'role_id' => 1,
            ),
            402 => array (
                'permission_id' => 424,
                'role_id' => 1,
            ),
            403 => array (
                'permission_id' => 425,
                'role_id' => 1,
            ),
            404 => array (
                'permission_id' => 426,
                'role_id' => 1,
            ),
            405 => array (
                'permission_id' => 427,
                'role_id' => 1,
            ),
            406 => array (
                'permission_id' => 428,
                'role_id' => 1,
            ),
            407 => array (
                'permission_id' => 429,
                'role_id' => 1,
            ),
            408 => array (
                'permission_id' => 430,
                'role_id' => 1,
            ),
            409 => array (
                'permission_id' => 431,
                'role_id' => 1,
            ),
            410 => array (
                'permission_id' => 432,
                'role_id' => 1,
            ),
            411 => array (
                'permission_id' => 433,
                'role_id' => 1,
            ),
            412 => array (
                'permission_id' => 434,
                'role_id' => 1,
            ),
            413 => array (
                'permission_id' => 435,
                'role_id' => 1,
            ),
            414 => array (
                'permission_id' => 436,
                'role_id' => 1,
            ),
            415 => array (
                'permission_id' => 437,
                'role_id' => 1,
            ),
            416 => array (
                'permission_id' => 438,
                'role_id' => 1,
            ),
            417 => array (
                'permission_id' => 439,
                'role_id' => 1,
            ),
            418 => array (
                'permission_id' => 440,
                'role_id' => 1,
            ),
            419 => array (
                'permission_id' => 441,
                'role_id' => 1,
            ),
            420 => array (
                'permission_id' => 442,
                'role_id' => 1,
            ),
            421 => array (
                'permission_id' => 443,
                'role_id' => 1,
            ),
            422 => array (
                'permission_id' => 444,
                'role_id' => 1,
            ),
            423 => array (
                'permission_id' => 445,
                'role_id' => 1,
            ),
            424 => array (
                'permission_id' => 446,
                'role_id' => 1,
            ),
            425 => array (
                'permission_id' => 447,
                'role_id' => 1,
            ),
            426 => array (
                'permission_id' => 448,
                'role_id' => 1,
            ),
            427 => array (
                'permission_id' => 449,
                'role_id' => 1,
            ),
            428 => array (
                'permission_id' => 450,
                'role_id' => 1,
            ),
            429 => array (
                'permission_id' => 451,
                'role_id' => 1,
            ),
            430 => array (
                'permission_id' => 452,
                'role_id' => 1,
            ),
            431 => array (
                'permission_id' => 453,
                'role_id' => 1,
            ),
            432 => array (
                'permission_id' => 454,
                'role_id' => 1,
            ),
            433 => array (
                'permission_id' => 455,
                'role_id' => 1,
            ),
            434 => array (
                'permission_id' => 456,
                'role_id' => 1,
            ),
            435 => array (
                'permission_id' => 457,
                'role_id' => 1,
            ),
            436 => array (
                'permission_id' => 458,
                'role_id' => 1,
            ),
            437 => array (
                'permission_id' => 459,
                'role_id' => 1,
            ),
            438 => array (
                'permission_id' => 460,
                'role_id' => 1,
            ),
            439 => array (
                'permission_id' => 461,
                'role_id' => 1,
            ),
            440 => array (
                'permission_id' => 462,
                'role_id' => 1,
            ),
            441 => array (
                'permission_id' => 463,
                'role_id' => 1,
            ),
            442 => array (
                'permission_id' => 464,
                'role_id' => 1,
            ),
            443 => array (
                'permission_id' => 465,
                'role_id' => 1,
            ),
            444 => array (
                'permission_id' => 466,
                'role_id' => 1,
            ),
            445 => array (
                'permission_id' => 467,
                'role_id' => 1,
            ),
            446 => array (
                'permission_id' => 468,
                'role_id' => 1,
            ),
            447 => array (
                'permission_id' => 469,
                'role_id' => 1,
            ),
            448 => array (
                'permission_id' => 470,
                'role_id' => 1,
            ),
            449 => array (
                'permission_id' => 471,
                'role_id' => 1,
            ),
            450 => array (
                'permission_id' => 472,
                'role_id' => 1,
            ),
            451 => array (
                'permission_id' => 473,
                'role_id' => 1,
            ),
            452 => array (
                'permission_id' => 474,
                'role_id' => 1,
            ),
            453 => array (
                'permission_id' => 475,
                'role_id' => 1,
            ),
            454 => array (
                'permission_id' => 476,
                'role_id' => 1,
            ),
            455 => array (
                'permission_id' => 477,
                'role_id' => 1,
            ),
            456 => array (
                'permission_id' => 478,
                'role_id' => 1,
            ),
            457 => array (
                'permission_id' => 479,
                'role_id' => 1,
            ),
            458 => array (
                'permission_id' => 480,
                'role_id' => 1,
            ),
            459 => array (
                'permission_id' => 481,
                'role_id' => 1,
            ),
            460 => array (
                'permission_id' => 482,
                'role_id' => 1,
            ),
            461 => array (
                'permission_id' => 483,
                'role_id' => 1,
            ),
            462 => array (
                'permission_id' => 484,
                'role_id' => 1,
            ),
            463 => array (
                'permission_id' => 485,
                'role_id' => 1,
            ),
            464 => array (
                'permission_id' => 486,
                'role_id' => 1,
            ),
            465 => array (
                'permission_id' => 487,
                'role_id' => 1,
            ),
            466 => array (
                'permission_id' => 488,
                'role_id' => 1,
            ),
            467 => array (
                'permission_id' => 489,
                'role_id' => 1,
            ),
            468 => array (
                'permission_id' => 490,
                'role_id' => 1,
            ),
            469 => array (
                'permission_id' => 491,
                'role_id' => 1,
            ),
            470 => array (
                'permission_id' => 492,
                'role_id' => 1,
            ),
            471 => array (
                'permission_id' => 493,
                'role_id' => 1,
            ),
            472 => array (
                'permission_id' => 494,
                'role_id' => 1,
            ),
            473 => array (
                'permission_id' => 495,
                'role_id' => 1,
            ),
            474 => array (
                'permission_id' => 496,
                'role_id' => 1,
            ),
            475 => array (
                'permission_id' => 497,
                'role_id' => 1,
            ),
            476 => array (
                'permission_id' => 498,
                'role_id' => 1,
            ),
            477 => array (
                'permission_id' => 499,
                'role_id' => 1,
            ),
            478 => array (
                'permission_id' => 500,
                'role_id' => 1,
            ),
            479 => array (
                'permission_id' => 501,
                'role_id' => 1,
            ),
            480 => array (
                'permission_id' => 502,
                'role_id' => 1,
            ),
            481 => array (
                'permission_id' => 503,
                'role_id' => 1,
            ),
            482 => array (
                'permission_id' => 504,
                'role_id' => 1,
            ),
            483 => array (
                'permission_id' => 505,
                'role_id' => 1,
            ),
            484 => array (
                'permission_id' => 506,
                'role_id' => 1,
            ),
            485 => array (
                'permission_id' => 507,
                'role_id' => 1,
            ),
            486 => array (
                'permission_id' => 508,
                'role_id' => 1,
            ),
            487 => array (
                'permission_id' => 509,
                'role_id' => 1,
            ),
            488 => array (
                'permission_id' => 510,
                'role_id' => 1,
            ),
            489 => array (
                'permission_id' => 511,
                'role_id' => 1,
            ),
            490 => array (
                'permission_id' => 512,
                'role_id' => 1,
            ),
            491 => array (
                'permission_id' => 513,
                'role_id' => 1,
            ),
            492 => array (
                'permission_id' => 514,
                'role_id' => 1,
            ),
            493 => array (
                'permission_id' => 515,
                'role_id' => 1,
            ),
            494 => array (
                'permission_id' => 516,
                'role_id' => 1,
            ),
            495 => array (
                'permission_id' => 517,
                'role_id' => 1,
            ),
            496 => array (
                'permission_id' => 518,
                'role_id' => 1,
            ),
            497 => array (
                'permission_id' => 519,
                'role_id' => 1,
            ),
            498 => array (
                'permission_id' => 520,
                'role_id' => 1,
            ),
            499 => array (
                'permission_id' => 521,
                'role_id' => 1,
            ),
            500 => array (
                'permission_id' => 522,
                'role_id' => 1,
            ),
            501 => array (
                'permission_id' => 523,
                'role_id' => 1,
            ),
            502 => array (
                'permission_id' => 524,
                'role_id' => 1,
            ),
            503 => array (
                'permission_id' => 525,
                'role_id' => 1,
            ),
            504 => array (
                'permission_id' => 526,
                'role_id' => 1,
            ),
            505 => array (
                'permission_id' => 527,
                'role_id' => 1,
            ),
            506 => array (
                'permission_id' => 528,
                'role_id' => 1,
            ),
            507 => array (
                'permission_id' => 529,
                'role_id' => 1,
            ),
            508 => array (
                'permission_id' => 530,
                'role_id' => 1,
            ),
            509 => array (
                'permission_id' => 531,
                'role_id' => 1,
            ),
            510 => array (
                'permission_id' => 532,
                'role_id' => 1,
            ),
            511 => array (
                'permission_id' => 533,
                'role_id' => 1,
            ),
            512 => array (
                'permission_id' => 534,
                'role_id' => 1,
            ),
            513 => array (
                'permission_id' => 535,
                'role_id' => 1,
            ),
            514 => array (
                'permission_id' => 536,
                'role_id' => 1,
            ),
            515 => array (
                'permission_id' => 537,
                'role_id' => 1,
            ),
            516 => array (
                'permission_id' => 538,
                'role_id' => 1,
            ),
            517 => array (
                'permission_id' => 539,
                'role_id' => 1,
            ),
            518 => array (
                'permission_id' => 540,
                'role_id' => 1,
            ),
            519 => array (
                'permission_id' => 541,
                'role_id' => 1,
            ),
            520 => array (
                'permission_id' => 542,
                'role_id' => 1,
            ),
            521 => array (
                'permission_id' => 543,
                'role_id' => 1,
            ),
            522 => array (
                'permission_id' => 544,
                'role_id' => 1,
            ),
            523 => array (
                'permission_id' => 545,
                'role_id' => 1,
            ),
            524 => array (
                'permission_id' => 546,
                'role_id' => 1,
            ),
            525 => array (
                'permission_id' => 547,
                'role_id' => 1,
            ),
            526 => array (
                'permission_id' => 548,
                'role_id' => 1,
            ),
            527 => array (
                'permission_id' => 549,
                'role_id' => 1,
            ),
            528 => array (
                'permission_id' => 550,
                'role_id' => 1,
            ),
            529 => array (
                'permission_id' => 551,
                'role_id' => 1,
            ),
            530 => array (
                'permission_id' => 552,
                'role_id' => 1,
            ),
            531 => array (
                'permission_id' => 553,
                'role_id' => 1,
            ),
            532 => array (
                'permission_id' => 554,
                'role_id' => 1,
            ),
            533 => array (
                'permission_id' => 555,
                'role_id' => 1,
            ),
            534 => array (
                'permission_id' => 556,
                'role_id' => 1,
            ),
            535 => array (
                'permission_id' => 557,
                'role_id' => 1,
            ),
            536 => array (
                'permission_id' => 558,
                'role_id' => 1,
            ),
            537 => array (
                'permission_id' => 559,
                'role_id' => 1,
            ),
            538 => array (
                'permission_id' => 560,
                'role_id' => 1,
            ),
            539 => array (
                'permission_id' => 561,
                'role_id' => 1,
            ),
            540 => array (
                'permission_id' => 562,
                'role_id' => 1,
            ),
            541 => array (
                'permission_id' => 563,
                'role_id' => 1,
            ),
            542 => array (
                'permission_id' => 564,
                'role_id' => 1,
            ),
            543 => array (
                'permission_id' => 565,
                'role_id' => 1,
            ),
            544 => array (
                'permission_id' => 566,
                'role_id' => 1,
            ),
            545 => array (
                'permission_id' => 567,
                'role_id' => 1,
            ),
            546 => array (
                'permission_id' => 568,
                'role_id' => 1,
            ),
            547 => array (
                'permission_id' => 569,
                'role_id' => 1,
            ),
            548 => array (
                'permission_id' => 570,
                'role_id' => 1,
            ),
            549 => array (
                'permission_id' => 571,
                'role_id' => 1,
            ),
            550 => array (
                'permission_id' => 572,
                'role_id' => 1,
            ),
            551 => array (
                'permission_id' => 573,
                'role_id' => 1,
            ),
            552 => array (
                'permission_id' => 574,
                'role_id' => 1,
            )
        ));

        
        \DB::table('role_has_permissions')->insert(array(
            0 => array (
                'permission_id' => 2,
                'role_id' => 8,
            ),
            1 => array (
                'permission_id' => 517,
                'role_id' => 8,
            ),
            2 => array (
                'permission_id' => 518,
                'role_id' => 8,
            ),
            3 => array (
                'permission_id' => 519,
                'role_id' => 8,
            ),
            4 => array (
                'permission_id' => 526,
                'role_id' => 8,
            ),
            5 => array (
                'permission_id' => 555,
                'role_id' => 8,
            )
        ));

    }
}
