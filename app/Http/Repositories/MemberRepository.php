<?php
namespace App\Http\Repositories;

use App\Models\Member;
use App\Models\Point;

class MemberRepository {
    public function getMemberWithPoints($phone) {
        return Member::with('points')->where('phone', $phone)->first();
    }

    public function queryMemberWithPoints() {
        return Member::with('points')->get();
    }

    public function createMember($phone) {
        $member = new Member();
        $member->phone = $phone;
        $member->save();
    }

    public function getMember($phone) {
        return Member::where('phone', $phone)->first();
    }

    public function update($name, $birthdate, $id_card) {

    }
}
