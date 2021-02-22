<?php
namespace App\Http\Controllers;

use App\Http\Services\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller {
    protected $memberService;

    public function __construct(MemberService $memberService) {
        $this->memberService = $memberService;
    }

    public function index(Request $request) {
        $view_data = [];
        $view_data['request'] = $request;
        $view_data['member_list'] = $this->memberService->queryMember();
        return view('admin.member.index', $view_data);
    }

    public function queryMember(Request $request) {
        return $this->memberService->queryMember();
    }

    function getMember(Request $request, $memberId) {
        return $this->memberService->getMember($memberId);
    }
}
