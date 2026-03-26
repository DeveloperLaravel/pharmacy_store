<?php

return [
    // User
    'user' => [
        'singular' => 'مستخدم',
        'plural' => 'المستخدمون',
        'fields' => [
            'name' => 'الاسم الكامل',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'password_confirmation' => 'تأكيد كلمة المرور',
            'is_active' => 'نشط',
            'roles' => 'الأدوار',
            'created_at' => 'تاريخ الإنشاء',
        ],
    ],

    // Category
    'category' => [
        'singular' => 'تصنيف',
        'plural' => 'التصنيفات',
        'fields' => [
            'name' => 'اسم التصنيف',
            'slug' => 'الاختصار',
            'description' => 'الوصف',
            'is_active' => 'نشط',
            'medicines_count' => 'الأدوية',
            'created_at' => 'تاريخ الإنشاء',
        ],
    ],

    // Medicine
    'medicine' => [
        'singular' => 'دواء',
        'plural' => 'الأدوية',
        'fields' => [
            'category' => 'التصنيف',
            'name' => 'الاسم',
            'scientific_name' => 'الاسم العلمي',
            'sku' => 'رمز SKU',
            'barcode' => 'الباركود',
            'dosage_form' => 'شكل الجرعة',
            'strength' => 'القوة',
            'unit' => 'الوحدة',
            'price' => 'السعر',
            'cost' => 'التكلفة',
            'quantity' => 'الكمية',
            'reorder_level' => 'مستوى إعادة الطلب',
            'expires_at' => 'تاريخ الانتهاء',
            'requires_prescription' => 'يتطلب وصفة',
            'is_active' => 'نشط',
        ],
    ],

    // Stock Movement
    'stock_movement' => [
        'singular' => 'حركة مخزون',
        'plural' => 'حركات المخزون',
        'fields' => [
            'medicine' => 'الدواء',
            'user' => 'تم بواسطة',
            'type' => 'النوع',
            'quantity' => 'الكمية',
            'reference' => 'المرجع',
            'notes' => 'الملاحظات',
            'performed_at' => 'وقت التنفيذ',
        ],
        'types' => [
            'in' => 'إدخال',
            'out' => 'إخراج',
            'adjustment' => 'تسوية',
        ],
    ],

    // Role
    'role' => [
        'singular' => 'دور',
        'plural' => 'الأدوار',
        'fields' => [
            'name' => 'اسم الدور',
            'permissions' => 'الصلاحيات',
            'permissions_count' => 'الصلاحيات',
            'created_at' => 'تاريخ الإنشاء',
        ],
    ],

    // Permission
    'permission' => [
        'singular' => 'صلاحية',
        'plural' => 'الصلاحيات',
        'fields' => [
            'name' => 'اسم الصلاحية',
            'created_at' => 'تاريخ الإنشاء',
        ],
    ],
];
