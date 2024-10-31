<?php

namespace App\Orchid\Resources;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Str;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class TopicResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Theme::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return "Страница Topic'ов";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('name')
                ->required()
                ->title('Название'),
            TextArea::make('description')
                ->required()
                ->title('Описание')
                ->rows(5)
                ->maxLength(999)
                ->help('Максимум 999 символов.'),
            TextArea::make('preview')
                ->title('Превью')
                ->rows(3)
                ->maxLength(254)
                ->help('Максимум 254 символа.'),
            Relation::make('user_id')
                ->required()
                ->fromModel(User::class, 'name')
                ->title('Автор')
                ->searchable(),
        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', 'ID')
                ->width('90px')
                ->sort(),
            TD::make('name', 'Название'),
            TD::make('preview', 'Превью')->render(function ($topic) {
                return Str::limit($topic->preview, 100);
            })->popover('Краткий обзор темы'),
            TD::make('description', 'Описание')->render(function ($topic) {
                return Str::limit($topic->description, 100);
            }),
            TD::make('created_at', 'Время создания')->render(function ($topic) {
                return $topic->created_at->diffForHumans();
            }),
            TD::make('updated_at', 'Время обновления')->render(function ($topic) {
                return $topic->created_at->diffForHumans();
            })->defaultHidden(),
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id', 'ID'),
            Sight::make('name', 'Название'),
            Sight::make('preview', 'Превью'),
            Sight::make('description', 'Описание'),
            Sight::make('created_at', 'Время создания')
                ->render(function ($topic) {
                    return $topic->created_at->diffForHumans();
            }),
            Sight::make('updated_at', 'Время обновления')
                ->render(function ($topic) {
                    return $topic->created_at->diffForHumans();
            }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }
}
