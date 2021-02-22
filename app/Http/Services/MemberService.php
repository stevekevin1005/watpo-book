<?php


namespace App\Http\Services;


use App\Http\Repositories\MemberRepository;

class MemberService {
    protected $memberRepository;

    public function __construct(MemberRepository $memberRepository) {
        $this->memberRepository = $memberRepository;
    }

    public function getMember($memberId) {
        return $this->memberRepository->getMemberWithPoints($memberId);
    }

    public function queryMember() {
        return $this->memberRepository->queryMemberWithPoints();
    }

    public function createMember($phone) {
        $existMember = $this->memberRepository->getMember($phone);
        if (empty($existMember)) {
            $this->memberRepository->createMember($phone);
        }
    }
}