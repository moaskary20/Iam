<?php

return [
    'direction' => 'rtl',
    'actions' => [
        'attach' => [
            'label' => 'إرفاق',
        ],
        'attach_another' => [
            'label' => 'إرفاق آخر',
        ],
        'cancel' => [
            'label' => 'إلغاء',
        ],
        'create' => [
            'label' => 'إنشاء',
        ],
        'create_another' => [
            'label' => 'إنشاء وإضافة آخر',
        ],
        'delete' => [
            'label' => 'حذف',
        ],
        'detach' => [
            'label' => 'فصل',
        ],
        'edit' => [
            'label' => 'تعديل',
        ],
        'export' => [
            'label' => 'تصدير',
        ],
        'force_delete' => [
            'label' => 'حذف نهائي',
        ],
        'import' => [
            'label' => 'استيراد',
        ],
        'restore' => [
            'label' => 'استعادة',
        ],
        'save' => [
            'label' => 'حفظ',
        ],
        'view' => [
            'label' => 'عرض',
        ],
    ],
    'empty' => [
        'heading' => 'لا توجد :label',
        'description' => 'أنشئ :label للبدء.',
    ],
    'fields' => [
        'bulk_select_page' => [
            'label' => 'تحديد/إلغاء تحديد جميع العناصر للعمليات المجمعة.',
        ],
        'bulk_select_record' => [
            'label' => 'تحديد/إلغاء تحديد العنصر :key للعمليات المجمعة.',
        ],
        'search' => [
            'label' => 'بحث',
            'placeholder' => 'بحث',
            'indicator' => 'بحث',
        ],
    ],
    'filters' => [
        'actions' => [
            'apply' => [
                'label' => 'تطبيق المرشحات',
            ],
            'remove' => [
                'label' => 'إزالة المرشح',
            ],
            'remove_all' => [
                'label' => 'إزالة جميع المرشحات',
            ],
            'reset' => [
                'label' => 'إعادة تعيين',
            ],
        ],
        'heading' => 'المرشحات',
        'indicator' => 'المرشحات النشطة',
        'multi_select' => [
            'placeholder' => 'الكل',
        ],
        'select' => [
            'placeholder' => 'الكل',
        ],
        'trashed' => [
            'label' => 'السجلات المحذوفة',
            'only_trashed' => 'المحذوفة فقط',
            'with_trashed' => 'مع المحذوفة',
            'without_trashed' => 'بدون المحذوفة',
        ],
    ],
    'grouping' => [
        'fields' => [
            'group' => [
                'label' => 'تجميع حسب',
                'placeholder' => 'تجميع حسب',
            ],
            'direction' => [
                'label' => 'اتجاه التجميع',
                'options' => [
                    'asc' => 'تصاعدي',
                    'desc' => 'تنازلي',
                ],
            ],
        ],
    ],
    'pagination' => [
        'label' => 'التنقل بين الصفحات',
        'overview' => 'عرض :first إلى :last من أصل :total نتيجة',
        'fields' => [
            'records_per_page' => [
                'label' => 'لكل صفحة',
                'options' => [
                    'all' => 'الكل',
                ],
            ],
        ],
        'actions' => [
            'go_to_page' => [
                'label' => 'الذهاب إلى الصفحة :page',
            ],
            'next' => [
                'label' => 'التالي',
            ],
            'previous' => [
                'label' => 'السابق',
            ],
        ],
    ],
    'reorder' => [
        'label' => 'إعادة ترتيب السجلات',
    ],
    'selection_indicator' => [
        'selected_count' => 'تم تحديد عنصر واحد|تم تحديد :count عنصر',
        'actions' => [
            'select_all' => [
                'label' => 'تحديد جميع :count',
            ],
            'deselect_all' => [
                'label' => 'إلغاء تحديد الكل',
            ],
        ],
    ],
    'sorting' => [
        'fields' => [
            'column' => [
                'label' => 'ترتيب حسب',
            ],
            'direction' => [
                'label' => 'اتجاه الترتيب',
                'options' => [
                    'asc' => 'تصاعدي',
                    'desc' => 'تنازلي',
                ],
            ],
        ],
    ],
];
