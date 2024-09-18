<?php

namespace App\AdminApi\Controllers\Traits;

use App\AdminApi\Repositories\ReferralProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use App\AdminApi\Resources\ReferralPrograms\ReferralProgramListResource;
use Illuminate\Validation\Rule;
use App\AdminApi\Resources\ReferralPrograms\ReferralProgramResource;
use App\AdminApi\Enums\ReferralProgram\SortBy;
use App\AdminApi\Helpers\Auth\CurrentAdmin;

trait ReferralProgramTrait
{
    public function createReferralProgram(ReferralProgramRepository $referralProgramRepository): Response
    {


        $data = $this->validate(
            [
            'program_name' => ['required', 'string', 'min:3' ,'max:255'],
            'program_title' => ['required','string', 'min:3' ,'max:255'],
            'partner_id' => ['required','int', Rule::unique('referral_program', 'partner_id'), Rule::in(CurrentAdmin::managedPartnerIds())],
            'is_active' => ['required', 'bool'],
            'description' => ['string', 'max:1000'],

            ]
        );

        $partnerId = (int) $data['partner_id'];

        return $this->response201(new ReferralProgramResource($referralProgramRepository->create($data['program_name'], $data['program_title'], $partnerId, $data['is_active'], $data['description'])));
    }

    public function getReferralPrograms(ReferralProgramRepository $referralProgramRepository): Response
    {

        $parameters = $this->validate([
            'partner_id' => ['integer', 'nullable'],
            'is_active' => ['bool', 'nullable'],
            'search_term' => ['string', 'nullable'],
            'sort_by' => ['nullable', 'string', Rule::enum(SortBy::class)]

        ]);

        $partnerId = $parameters['partner_id'] ?? null;
        $isActive = $parameters['is_active'] ?? null;
        $searchTerm = $parameters['search_term'] ?? null;
        $sortBy = isset($parameters['sort_by']) ? SortBy::from($parameters['sort_by']) : null;
        $managedPartnerIds = CurrentAdmin::managedPartnerIds();



        $referralPrograms = $referralProgramRepository->scopeByPartnerIds($managedPartnerIds)->getFilteredPrograms($partnerId, $isActive, $searchTerm, $sortBy);


        return $this->response200(ReferralProgramListResource::collection($referralPrograms));
    }


    public function getReferralProgram(string $id, ReferralProgramRepository $referralProgramRepository): Response
    {
        $managedPartnerIds = CurrentAdmin::managedPartnerIds();
        $referral = $referralProgramRepository ->scopeByPartnerIds($managedPartnerIds)-> getReferralById($id);
        return $this->response200(new ReferralProgramResource($referral));
    }

    public function updateReferral(string $id, ReferralProgramRepository $referralProgramRepository): Response
    {

        $data = $this->validate(
            [
            'program_name' => ['required', 'string', 'min:3' ,'max:255'],
            'program_title' => ['required','string', 'min:3' ,'max:255'],
            'partner_id' => ['required','int', Rule::unique('referral_program', 'partner_id'), Rule::in(CurrentAdmin::managedPartnerIds())],
            'is_active' => ['required', 'bool'],
            'description' => ['required', 'string', 'max:1000'],

            ]
        );

        $partnerId = (int) $data['partner_id'];
        $managedPartnerIds = CurrentAdmin::managedPartnerIds();
        return $this->response202(new ReferralProgramResource($referralProgramRepository->scopeByPartnerIds($managedPartnerIds)->updateReferralById($id, $data['program_name'], $data['program_title'], $partnerId, $data['is_active'], $data['description'], $managedPartnerIds)));
    }


    public function deleteReferral(string $id, ReferralProgramRepository $referralProgramRepository): Response
    {
        $managedPartnerIds = CurrentAdmin::managedPartnerIds();
        $referralProgramRepository->scopeByPartnerIds($managedPartnerIds)->destroy($id);
        return $this->response204();
    }
}
