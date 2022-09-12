<?php

return [

    /*
|--------------------------------------------------------------------------
| CodeGenerator config overrides
|--------------------------------------------------------------------------
|
| It is a good idea to sperate your configuration form the code-generator's
| own configuration. This way you won't lose any settings/preference
| you have when upgrading to a new version of the package.
|
| Additionally, you will always know any the configuration difference between
| the default config than your own.
|
| To override the setting that is found in the codegenerator.php file, you'll
| need to create identical key here with a different value
|
| IMPORTANT: When overriding an option that is an array, the configurations
| are merged together using php's array_merge() function. This means that
| any option that you list here will take presence during a conflict in keys.
|
| EXAMPLE: The following addition to this file, will add another entry in
| the common_definitions collection
|
|   'common_definitions' =>
|   [
|       [
|           'match' => '*_at',
|           'set' => [
|               'css-class' => 'datetime-picker',
|           ],
|       ],
|   ],
|
 */
    /*
    |--------------------------------------------------------------------------
    | Patterns to use to pre-set field's properties.
    |--------------------------------------------------------------------------
    |
    | To make constructing fields easy, the code-generator scans the field's name
    | for a matching pattern. If the name matches any of these patterns, the
    | field's properties will be set accordingly. Defining pattern will save
    | you from having to re-define the properties for common fields.
    |
     */

    /*
    |--------------------------------------------------------------------------
    | The default template to use.
    |--------------------------------------------------------------------------
    |
    | Here you change the stub templates to use when generating code.
    | You can duplicate the 'default' template folder and call it whatever
    | template name you like 'ex. skyblue'. Now, you can change the stubs to
    | have your own templates generated.
    |
    |
    | IMPORTANT: It is not recommended to modify the default template, rather
    | create a new template. If you modify the default template and then
    | executed 'php artisan vendor:publish' command, will override your changes!
    |
     */
    'template' => 'admin-lte',

    'common_definitions' => [
        [
            'match' => '*',
            'set' => [
                // You may use any of the field templates to create the label
                'labels' => '[% field_name_title %]',
            ],
        ],
        [
            'match' => 'id',
            'set' => [
                'is-on-form' => false,
                'is-on-index' => true,
                'is-on-show' => false,
                'html-type' => 'hidden',
                'data-type' => 'integer',
                'is-primary' => true,
                'is-auto-increment' => true,
                'is-nullable' => false,
                'is-unsigned' => true,
            ],
        ],
        [
            'match' => ['title', 'name', 'label', 'subject', 'head*'],
            'set' => [
                'is-nullable' => false,
                'data-type' => 'string',
                'data-type-params' => [255],
            ],
        ],
        [
            'match' => ['*count*', 'total*', '*number*'],
            'set' => [
                'html-type' => 'number',
            ],
        ],
        [
            'match' => ['description*', 'detail*', 'note*', 'message*'],
            'set' => [
                'is-on-index' => false,
                'html-type' => 'textarea',
                'data-type-params' => [1000],
            ],
        ],
        [
            'match' => ['picture', 'file', 'photo', 'avatar', '*_image'],
            'set' => [
                'is-on-index' => false,
                'html-type' => 'file',
            ],
        ],
        [
            'match' => ['*password*'],
            'set' => [
                'html-type' => 'password',
            ],
        ],
        [
            'match' => ['*email*'],
            'set' => [
                'html-type' => 'email',
            ],
        ],
        [
            'match' => ['*_id', '*_by'],
            'set' => [
                'data-type' => 'integer',
                'html-type' => 'select',
                'is-nullable' => false,
                'is-unsigned' => true,
                'is-index' => true,
            ],
        ],
        [
            'match' => ['*_at'],
            'set' => [
                'data-type' => 'datetime',
            ],
        ],
        [
            'match' => ['created_at', 'updated_at', 'deleted_at'],
            'set' => [
                'data-type' => 'datetime',
                'is-on-form' => false,
                'is-on-index' => false,
                'is-on-show' => true,
            ],
        ],
        [
            'match' => ['*_date', 'date_*', 'date'],
            'set' => [
                'data-type' => 'date',
                'date-format' => 'm/d/Y',
            ],
        ],
        [
            'match' => ['is_*', 'has_*'],
            'set' => [
                'data-type' => 'boolean',
                'html-type' => 'checkbox',
                'is-nullable' => false,
                'options' => ["No", "Yes"],
            ],
        ],
        [
            'match' => 'created_by',
            'set' => [
                'title' => 'Creator',
                'data-type' => 'integer',
                'is-on-form' => false,
                'foreign-relation' => [
                    'name' => 'creator',
                    'type' => 'belongsTo',
                    'params' => [
                        'App\\Models\User',
                        'created_by',
                    ],
                    'field' => 'name',
                ],
                'on-store' => 'Illuminate\Support\Facades\Auth::user()->id;',
            ],
        ],
        [
            'match' => ['updated_by', 'modified_by'],
            'set' => [
                'title' => 'Updater',
                'data-type' => 'integer',
                'foreign-relation' => [
                    'name' => 'updater',
                    'type' => 'belongsTo',
                    'params' => [
                        'App\\User',
                        'updated_by',
                    ],
                    'field' => 'name',
                ],
                'on-update' => 'Illuminate\Support\Facades\Auth::Id();',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Eloquent method to html-type mapping.
    |--------------------------------------------------------------------------
    |
    | This is the mapping used to convert database-column into html field
    |
     */
    'eloquent_type_to_html_type' => [
        'char' => 'text',
        'date' => 'date',
        'dateTime' => 'text',
        'dateTimeTz' => 'text',
        'bigIncrements' => 'number',
        'binary' => 'textarea',
        'boolean' => 'checkbox',
        'decimal' => 'number',
        'double' => 'number',
        'enum' => 'select',
        'float' => 'number',
        'integer' => 'number',
        'ipAddress' => 'text',
        'json' => 'checkbox',
        'jsonb' => 'checkbox',
        'longText' => 'textarea',
        'macAddress' => 'text',
        'mediumInteger' => 'number',
        'mediumText' => 'textarea',
        'string' => 'text',
        'text' => 'textarea',
        'time' => 'text',
        'timeTz' => 'text',
        'tinyInteger' => 'number',
        'timestamp' => 'text',
        'timestampTz' => 'text',
        'unsignedBigInteger' => 'number',
        'unsignedInteger' => 'number',
        'unsignedMediumInteger' => 'number',
        'unsignedSmallInteger' => 'number',
        'unsignedTinyInteger' => 'number',
        'uuid' => 'text',
    ],

    /*
    |--------------------------------------------------------------------------
    | Patterns to use to generate the html placeholders.
    |--------------------------------------------------------------------------
    |
    | When creating the fields, the code generator follows a pattern to generate
    | placeholders for the html code. Here you can define which html-types should
    | the generator create placeholder for. Also, you can define how would you like
    | the text to read when no placeholder is assigned.
    |
    | The follwowing templates can be used to. assuming the field name is owner_name
    | [% field_name %]                   <=> "owner name"
    | [% field_name_sentence %]          <=> "Owner name"
    | [% field_name_plural %]            <=> "owner names"
    | [% field_name_plural_title %]      <=> "Owner Names"
    | [% field_name_snake %]             <=> "owner_name"
    | [% field_name_studly %]            <=> "OwnerName"
    | [% field_name_slug %]              <=> "owner-name"
    | [% field_name_kebab %]             <=> "owner-name"
    | [% field_name_title %]             <=> "Owner Name"
    | [% field_name_title_upper %]       <=> "OWNER NAME"
    | [% field_name_plural_variable %]   <=> "ownerNames"
    | [% field_name_singular_variable %] <=> "ownerName"
    |
     */
    'placeholder_by_html_type' => [
        'text' => 'Enter [% field_name %]',
        'number' => 'Enter [% field_name %]',
        'password' => 'Enter [% field_name %]',
        'email' => 'Enter [% field_name %]',
        'date' => 'format: d/m/Y',
        'select' => 'Select [% field_name %]',
        'multipleSelect' => 'Select [% field_name %]',
    ],

];
