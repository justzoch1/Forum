<?php

namespace App\Orchid\Resources;

use App\Models\User;
use Illuminate\Support\Str;
use Orchid\Crud\Resource;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class AnswerResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Answer::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return "Страница ответов";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('content')->title('Контент'),
            Relation::make('comment_id')
                ->required()
                ->fromModel(User::class, 'id')
                ->title('ID комментария')
                ->searchable(),
            Relation::make('user_id')
                ->required()
                ->fromModel(User::class, 'name')
                ->title('Автор')
                ->searchable(),
            Relation::make('receiver_id')
                ->required()
                ->fromModel(User::class, 'name')
                ->title('Адресат')
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
                    return $model->author->name;
                }),

            TD::make('receiver_id', 'Адресат')
                ->render(function ($model) {
                    return $model->receiver->name;
                }),

            TD::make('comment_id', 'ID комментария')->render(function ($model) {
                return Link::make($model->comment->id)
                    ->href(env('APP_URL') . '/admin/crud/view/comment-resources/' . $model->comment->id);
            }),

            TD::make('content', 'Контент')
                ->render(function ($model) {
                    return Str::limit($model->content, 70);
                }),

            TD::make('created_at', 'Дата создания')
                ->render(function ($topic) {
                    return $topic->created_at->diffForHumans();
                }),

            TD::make('updated_at', 'Дата обновления')
                ->render(function ($topic) {
                    return $topic->updated_at->diffForHumans();
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
            Sight::make('user_id', 'Автор')
                ->render(function ($model) {
                    return $model->author->name;
                }),
            Sight::make('receiver_id', 'Адресат')
                ->render(function ($model) {
                    return $model->receiver->name;
                }),
            Sight::make('comment_id', 'ID комментария')
                ->render(function ($model) {
                    return $model->comment->id;
                }),
            Sight::make('content', 'Контент'),
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
