<?php

namespace App\GPT\Actions\generateContent;

use MalteKuhr\LaravelGPT\GPTAction;
use Closure;

class generateContentGPTAction extends GPTAction
{

    protected $name;
    protected $categories;

    public function __construct(string $name, array $categories)
    {
        $categoryNames = array_map(function($category) {
            return $category['name'];
        }, $categories);
        $this->name = $name;
        $this->categories = implode(', ', $categoryNames);
    }

    /**
     * The message which explains the assistant what to do and which rules to follow.
     *
     * @return string|null
     */
    public function systemMessage(): ?string
    {
        return "Bạn là một nhà văn sáng tạo. Hãy tạo ra một nội dung truyện tranh dài 500 chữ dựa trên các tiêu chí sau: Tên truyện: {$this->name}, Thể loại: {$this->categories}. Nội dung phải hấp dẫn và liên quan đến các thể loại này. Nội dung phải được viết bằng tiếng Việt.";
    }

    /**
     * Specifies the function to be invoked by the model. The function is implemented as a
     * Closure which may take parameters that are provided by the model. If extra arguments
     * are included in the documentation to optimize model's performance (by allowing it more
     * thinking time), these can be disregarded by not including them within the Closure
     * parameters.
     *
     * @return Closure
     */
    public function function(): Closure
    {
        return function (string $content): mixed {
            return [
                'content' => $content
            ];
        };
    }

    /**
     * Defines the rules for input validation and JSON schema generation. Override this
     * method to provide custom validation rules for the function. The documentation will
     * have the same order as the rules are defined in this method.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string'
        ];
    }
}
