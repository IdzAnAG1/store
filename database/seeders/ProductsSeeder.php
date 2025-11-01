<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Сначала убедимся, что категории существуют
        $this->seedCategories();

        // Получаем категории по имени → получаем их реальные ID
        $categories = DB::table('categories')
            ->pluck('category_id', 'category_name')
            ->toArray();

        // Теперь создаём продукты, используя реальные ID
        $products = [
            ['name' => 'Dell XPS 13', 'description' => 'Ультрабук с Intel Core i7, 16 ГБ ОЗУ, 512 ГБ SSD', 'price' => 95000.00, 'category_id' => 1, 'stock_quantity' => 15],
            ['name' => 'MacBook Air M2', 'description' => 'Apple MacBook Air с чипом M2, 8 ГБ ОЗУ, 256 ГБ SSD', 'price' => 110000.00, 'category_id' => 1, 'stock_quantity' => 10],
            ['name' => 'Dell XPS 13', 'description' => 'Ультрабук с Intel Core i7, 16 ГБ ОЗУ, 512 ГБ SSD', 'price' => 95000.00, 'category_id' => 1, 'stock_quantity' => 15],
            ['name' => 'MacBook Air M2', 'description' => 'Apple MacBook Air с чипом M2, 8 ГБ ОЗУ, 256 ГБ SSD', 'price' => 110000.00, 'category_id' => 1, 'stock_quantity' => 10],
            ['name' => 'Lenovo ThinkPad X1 Carbon', 'description' => 'Бизнес-ноутбук с Intel Core i5, 16 ГБ ОЗУ', 'price' => 85000.00, 'category_id' => 1, 'stock_quantity' => 8],
            ['name' => 'HP Spectre x360', 'description' => 'Конвертируемый ноутбук с сенсорным экраном', 'price' => 92000.00, 'category_id' => 1, 'stock_quantity' => 6],
            ['name' => 'ASUS ROG Zephyrus G14', 'description' => 'Игровой ноутбук с AMD Ryzen 9 и RTX 4060', 'price' => 130000.00, 'category_id' => 1, 'stock_quantity' => 5],

            // Смартфоны
            ['name' => 'iPhone 15 Pro', 'description' => '6.1" OLED, 256 ГБ, титановый корпус', 'price' => 120000.00, 'category_id' => 2, 'stock_quantity' => 20],
            ['name' => 'Samsung Galaxy S24 Ultra', 'description' => '6.8" QHD+, 512 ГБ, S Pen в комплекте', 'price' => 115000.00, 'category_id' => 2, 'stock_quantity' => 18],
            ['name' => 'Google Pixel 8 Pro', 'description' => 'Чистый Android, отличная камера', 'price' => 85000.00, 'category_id' => 2, 'stock_quantity' => 12],
            ['name' => 'Xiaomi 14 Pro', 'description' => 'Snapdragon 8 Gen 3, 120Hz AMOLED', 'price' => 75000.00, 'category_id' => 2, 'stock_quantity' => 25],
            ['name' => 'OnePlus 12', 'description' => 'Флагман с быстрой зарядкой 100 Вт', 'price' => 70000.00, 'category_id' => 2, 'stock_quantity' => 22],

            // Процессоры
            ['name' => 'Intel Core i9-14900K', 'description' => '24 ядра, до 6.0 ГГц, для геймеров и стримеров', 'price' => 55000.00, 'category_id' => 3, 'stock_quantity' => 30],
            ['name' => 'AMD Ryzen 9 7950X', 'description' => '16 ядер, 32 потока, AM5', 'price' => 52000.00, 'category_id' => 3, 'stock_quantity' => 28],
            ['name' => 'Intel Core i5-14600K', 'description' => '14 ядер, отличное соотношение цена/производительность', 'price' => 28000.00, 'category_id' => 3, 'stock_quantity' => 40],
            ['name' => 'AMD Ryzen 7 7700X', 'description' => '8 ядер, 16 потоков, энергоэффективный', 'price' => 26000.00, 'category_id' => 3, 'stock_quantity' => 35],
            ['name' => 'Intel Core i3-14100', 'description' => '4 ядра, 8 потоков, для офиса и учебы', 'price' => 12000.00, 'category_id' => 3, 'stock_quantity' => 50],

            // Видеокарты
            ['name' => 'NVIDIA RTX 4090', 'description' => '24 ГБ GDDR6X, топовая игровая видеокарта', 'price' => 180000.00, 'category_id' => 4, 'stock_quantity' => 3],
            ['name' => 'AMD Radeon RX 7900 XTX', 'description' => '24 ГБ GDDR6, конкурент RTX 4080', 'price' => 110000.00, 'category_id' => 4, 'stock_quantity' => 7],
            ['name' => 'NVIDIA RTX 4070 Ti', 'description' => '12 ГБ GDDR6X, 1440p/4K гейминг', 'price' => 70000.00, 'category_id' => 4, 'stock_quantity' => 12],
            ['name' => 'NVIDIA RTX 4060', 'description' => '8 ГБ, энергоэффективная видеокарта', 'price' => 35000.00, 'category_id' => 4, 'stock_quantity' => 25],
            ['name' => 'AMD Radeon RX 7700 XT', 'description' => '12 ГБ, отличная производительность за свои деньги', 'price' => 45000.00, 'category_id' => 4, 'stock_quantity' => 18],

            // Оперативная память
            ['name' => 'Corsair Vengeance DDR5 32 ГБ (2x16)', 'description' => '6000 МГц, CL36', 'price' => 12000.00, 'category_id' => 5, 'stock_quantity' => 40],
            ['name' => 'G.Skill Trident Z5 DDR5 64 ГБ', 'description' => '6400 МГц, RGB подсветка', 'price' => 22000.00, 'category_id' => 5, 'stock_quantity' => 20],
            ['name' => 'Kingston Fury Beast DDR4 16 ГБ', 'description' => '3200 МГц, без RGB', 'price' => 6000.00, 'category_id' => 5, 'stock_quantity' => 60],
            ['name' => 'Crucial DDR5 32 ГБ', 'description' => '5600 МГц, надёжная память', 'price' => 11000.00, 'category_id' => 5, 'stock_quantity' => 35],

            // SSD
            ['name' => 'Samsung 980 Pro 2 ТБ', 'description' => 'NVMe M.2, до 7000 МБ/с', 'price' => 18000.00, 'category_id' => 6, 'stock_quantity' => 25],
            ['name' => 'WD Black SN850X 1 ТБ', 'description' => 'Игровой NVMe SSD', 'price' => 10000.00, 'category_id' => 6, 'stock_quantity' => 30],
            ['name' => 'Crucial P5 Plus 2 ТБ', 'description' => 'Надёжный и быстрый SSD', 'price' => 14000.00, 'category_id' => 6, 'stock_quantity' => 28],
            ['name' => 'Kingston NV2 1 ТБ', 'description' => 'Бюджетный NVMe SSD', 'price' => 5500.00, 'category_id' => 6, 'stock_quantity' => 50],

            // Мониторы
            ['name' => 'LG UltraFine 32UN880-B', 'description' => '32" 4K, HDR, эргономичная подставка', 'price' => 65000.00, 'category_id' => 7, 'stock_quantity' => 10],
            ['name' => 'ASUS ROG Swift PG279QM', 'description' => '27" QHD, 240 Гц, для киберспорта', 'price' => 80000.00, 'category_id' => 7, 'stock_quantity' => 6],
            ['name' => 'Dell UltraSharp U2723QE', 'description' => '27" 4K, IPS Black, для дизайнеров', 'price' => 70000.00, 'category_id' => 7, 'stock_quantity' => 8],
            ['name' => 'AOC Gaming C24G2', 'description' => '24" Full HD, 144 Гц, VA матрица', 'price' => 18000.00, 'category_id' => 7, 'stock_quantity' => 20],

            // Клавиатуры
            ['name' => 'Keychron K8 Pro', 'description' => 'Механическая, горячая замена переключателей, Bluetooth', 'price' => 9000.00, 'category_id' => 8, 'stock_quantity' => 30],
            ['name' => 'Logitech MX Keys', 'description' => 'Тихая мембранная клавиатура, подсветка', 'price' => 10000.00, 'category_id' => 8, 'stock_quantity' => 25],
            ['name' => 'Razer BlackWidow V4', 'description' => 'Геймерская механическая с RGB', 'price' => 14000.00, 'category_id' => 8, 'stock_quantity' => 15],

            // Мыши
            ['name' => 'Logitech G502 X', 'description' => 'Геймерская мышь с HERO сенсором', 'price' => 7000.00, 'category_id' => 9, 'stock_quantity' => 40],
            ['name' => 'Razer DeathAdder V3', 'description' => 'Эргономичная, 30K DPI', 'price' => 6500.00, 'category_id' => 9, 'stock_quantity' => 35],
            ['name' => 'Apple Magic Mouse', 'description' => 'Беспроводная, с мультитач поверхностью', 'price' => 8000.00, 'category_id' => 9, 'stock_quantity' => 20],

            // Наушники
            ['name' => 'Sony WH-1000XM5', 'description' => 'Беспроводные с шумоподавлением', 'price' => 25000.00, 'category_id' => 10, 'stock_quantity' => 18],
            ['name' => 'Apple AirPods Pro (2nd gen)', 'description' => 'С активным шумоподавлением', 'price' => 22000.00, 'category_id' => 10, 'stock_quantity' => 25],
            ['name' => 'SteelSeries Arctis Nova Pro', 'description' => 'Геймерские наушники с заменяемыми аккумуляторами', 'price' => 30000.00, 'category_id' => 10, 'stock_quantity' => 12],

            // Зарядные устройства и аксессуары
            ['name' => 'Anker 737 Charger (GaNPrime 120W)', 'description' => 'Компактное зарядное устройство для ноутбуков и телефонов', 'price' => 6000.00, 'category_id' => 11, 'stock_quantity' => 50],
            ['name' => 'Baseus 100W USB-C кабель', 'description' => 'Для быстрой зарядки и передачи данных', 'price' => 1500.00, 'category_id' => 11, 'stock_quantity' => 100],
            ['name' => 'Накладная веб-камера Logitech C920', 'description' => 'Full HD 1080p, для стримов и Zoom', 'price' => 8000.00, 'category_id' => 11, 'stock_quantity' => 30],

        ];

        // Добавляем created_at / updated_at
        $now = now();
        $products = array_map(fn($p) => array_merge($p, ['created_at' => $now, 'updated_at' => $now]), $products);

        DB::table('products')->insert($products);
    }

    private function seedCategories(): void
    {
        if (DB::table('categories')->count() > 0) {
            return; // уже есть категории — не трогаем
        }

        DB::table('categories')->insert([
            ['category_name' => 'Ноутбуки', 'description' => 'Портативные компьютеры различного класса', 'parent_category_id' => null],
            ['category_name' => 'Смартфоны', 'description' => 'Мобильные телефоны с расширенными функциями', 'parent_category_id' => null],
            ['category_name' => 'Процессоры', 'description' => 'Центральные процессоры для ПК и серверов', 'parent_category_id' => null],
            ['category_name' => 'Видеокарты', 'description' => 'Графические адаптеры для игр и работы', 'parent_category_id' => null],
            ['category_name' => 'Оперативная память', 'description' => 'Модули ОЗУ для настольных ПК и ноутбуков', 'parent_category_id' => null],
            ['category_name' => 'SSD и накопители', 'description' => 'Твердотельные накопители и HDD', 'parent_category_id' => null],
            ['category_name' => 'Мониторы', 'description' => 'Компьютерные дисплеи различных размеров', 'parent_category_id' => null],
            ['category_name' => 'Клавиатуры', 'description' => 'Проводные и беспроводные клавиатуры', 'parent_category_id' => null],
            ['category_name' => 'Компьютерные мыши', 'description' => 'Геймерские и офисные мыши', 'parent_category_id' => null],
            ['category_name' => 'Наушники и аудио', 'description' => 'Аудиотехника для ПК и мобильных устройств', 'parent_category_id' => null],
            ['category_name' => 'Аксессуары', 'description' => 'Кабели, док-станции, подставки и прочее', 'parent_category_id' => null],
        ]);
    }
}
