<?php

namespace App\AdminApi\Repositories;

use App\ReferralProgram;
use App\Components\PlatformSync\EntityCodeVo;
use App\Services\ImageManager\ImageManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\AdminApi\Resources\ReferralPrograms\ReferralProgramListResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\AdminApi\Enums\ReferralProgram\SortBy;

class ReferralProgramRepository
{
    protected ?array $partnerIds = null;

    public function scopeByPartnerIds(array $partnerIds)
    {
        $this->partnerIds = $partnerIds;

        return $this;
    }
    public function create(string $programName, string $programTitle, int $partnerId, bool $isActive, string $description): ReferralProgram
    {
        $referral = new ReferralProgram();
        $referral->program_name = $programName;
        $referral->program_title = $programTitle;
        $referral->partner_id = $partnerId;
        $referral->is_active = $isActive;
        $referral->description = $description;
        $referral->save();
        return $referral;
    }


    /**
    * @return Collection <int, ReferralProgram>
    */
    public function getFilteredPrograms(?int $partnerId, ?bool $isActive, ?string $searchTerm, ?SortBy $sortBy): Collection
    {

        $query = $this->initialQuery();

        $query->when(isset($partnerId), function ($q) use ($partnerId) {
            $q->where('partner_id', $partnerId);
        })
            ->when(isset($isActive), function ($q) use ($isActive) {
                $q->where('is_active', $isActive);
            })-> when(!empty($searchTerm), function ($q) use ($searchTerm) {
                $q->where('program_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('program_title', 'like', '%' . $searchTerm . '%');
            });



        $orderColumn = null;
        $orderDirection = null;
        if ($sortBy) {
            if ($sortBy === SortBy::CREATED_AT_ASC) {
                $orderColumn = 'created_at';
                $orderDirection = 'ASC';
            } elseif ($sortBy === SortBy::CREATED_AT_DESC) {
                $orderColumn = 'created_at';
                $orderDirection = 'DESC';
            }

            $query->orderBy($orderColumn, $orderDirection ?? 'ASC');
        } else {
            $query->orderBy('id', 'ASC');
        }
        return $query->get();
    }

   /*
   @return \Illuminate\Database\Eloquent\Builder<App\ReferralProgram>
   */
    public function initialQuery(): Builder
    {
        $query = ReferralProgram::query()
        ->when(!empty($this->partnerIds), function ($query) {
            $query->whereIn('partner_id', $this->partnerIds);
        });


        return $query;
    }

    public function findReferralProgramByIdOrFail(string $id): ReferralProgram
    {
        return $this->initialQuery()->where('id', $id)->firstOrFail();
    }

    public function getReferralById(string $id): ?ReferralProgram
    {
        return $this->initialQuery()
        ->join('partner', 'referral_program.partner_id', '=', 'partner.id')
        ->select('referral_program.id', 'referral_program.program_name', 'referral_program.program_title', 'partner.name', 'referral_program.partner_id', 'referral_program.is_active', 'referral_program.description', 'referral_program.created_at')
        ->orderBy('id', 'asc')
        ->find($id);
    }


    public function updateReferralById(string $id, string $programName, string $programTitle, int $partnerId, bool $isActive, string $description, ?array $managedPartnerIds = null): ReferralProgram
    {
        $referral = $this->initialQuery()->findReferralProgramByIdOrFail($id);


        $referral->program_name = $programName;
        $referral->program_title = $programTitle;
        $referral->partner_id = $partnerId;
        $referral->is_active = $isActive;
        $referral->description = $description;
        $referral->save();
        return $referral;
    }


    public function destroy(string $id): void
    {
        $referral = $this->findReferralProgramByIdOrFail($id);



        $referral->delete();
    }
}
