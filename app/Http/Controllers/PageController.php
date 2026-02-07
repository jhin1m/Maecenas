<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;

class PageController extends Controller
{
    /**
     * Hiển thị trang tĩnh với tiêu đề và nội dung tương ứng.
     * Dùng chung 1 view "users.pages.static" cho tất cả các trang.
     */
    private function showStaticPage(string $title, string $view)
    {
        SEOTools::setTitle($title . ' - ' . env('APP_NAME'));

        return view('users.pages.static', [
            'title' => $title,
            'contentView' => $view,
        ]);
    }

    public function showAbout()
    {
        return $this->showStaticPage('Về chúng tôi', 'users.pages.about');
    }

    public function showContact()
    {
        return $this->showStaticPage('Phương thức liên hệ', 'users.pages.contact');
    }

    public function showTerms()
    {
        return $this->showStaticPage('Điều khoản dịch vụ', 'users.pages.terms');
    }

    public function showDisclaimer()
    {
        return $this->showStaticPage('Tuyên bố miễn trừ trách nhiệm', 'users.pages.disclaimer');
    }

    public function showPrivacy()
    {
        return $this->showStaticPage('Chính sách bảo mật', 'users.pages.privacy');
    }
}
