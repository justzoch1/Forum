<?php

namespace App\Orchid\Resources;

use App\Models\Theme;
use App\Models\User;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\QueryFilter;
use Illuminate\Support\Str;
use Orchid\Crud\Filters\DefaultSorted;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use App\Models\Comment;
use Orchid\Screen\Fields\Input;

class CommentResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Comment::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return "Страница комментариев";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            TextArea::make('content')
                ->required()
                ->title('Описание')
                ->rows(5)
                ->maxLength(999)
                ->help('Максимум 999 символов.'),
            Relation::make('user_id')
                ->required()
                ->fromModel(User::class, 'name')
                ->title('Автор')
                ->searchable(),
            Select::make('status')
                ->required()
                ->title('Статус')
                ->options([
                    'pending' => 'В ожидании',
                    'approved' => 'Одобрен',
                    'rejected' => 'Отклонен',
                ])
                ->default('pending'),
            Relation::make('theme_id')
                ->required()
                ->fromModel(Theme::class, 'name')
                ->title('Тема')
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
                ->width('100px')
                ->sort(),

            TD::make('user_id', 'Автор')
                ->render(function ($model) {
                    return $model->user->name ?? 'Не указан';
                }),

            TD::make('content', 'Контент')
                ->render(function ($model) {
                    return Str::limit($model->content, 70);
                }),

            TD::make('status', 'Статус')
                ->render(function ($model) {
                    return match ($model->status) {
                        'pending' => 'В ожидании',
                        'approved' => 'Одобрен',
                        'rejected' => 'Отклонен',
                        default => 'Неизвестный статус',
                    };
                })->width('100px'),

            TD::make('theme_id', 'Тема')
                ->render(function ($model) {
                    return $model->theme->name;
                }),

            TD::make('created_at', 'Дата создания')
                ->render(function ($topic) {
                    return $topic->created_at->diffForHumans();
                }),

            TD::make('updated_at', 'Дата обновления')
                ->render(function ($topic) {
                    return $topic->updated_at->diffForHumans();
                }),
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
            Sight::make('user_id', 'Автор')
                ->render(function ($model) {
                    return $model->user->name ?? 'Не указан';
                }),
            Sight::make('content', 'Контент'),
            Sight::make('status', 'Статус')
                ->render(function ($model) {
                    return match ($model->status) {
                        'pending' => 'В ожидании',
                        'approved' => 'Одобрен',
                        'rejected' => 'Отклонен',
                        default => 'Неизвестный статус',
                    };
                }),
            Sight::make('theme_id', 'Тема')
                ->render(function ($model) {
                    return $model->theme->name ?? 'Не указана';
                }),
            Sight::make('created_at', 'Дата создания')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
            Sight::make('updated_at', 'Дата обновления')
                ->render(function ($model) {
                    return $model->updated_at->toDateTimeString();
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
