<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\DTO\UpdateMainProfileDTO;
use App\DTO\UpdateSecondProfileDTO;
use App\DTO\UpdatePartnerProfileDTO;
use App\Interfaces\AnketVisitInterface;
use App\Interfaces\AnketEvaluationInterface;
use App\Services\AnkService;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		private UserInterface $repository,
		private AnkService $ankService,
		private AnketVisitInterface $anketVisitRepository,
		private AnketEvaluationInterface $anketEvaluationRepository
	) {}

	/**
	 * show a profile page
	 */
	public function show(Request $request, int $id, string $mode): array
	{
		$profile = $this->repository->getById($id);
		$this->ankService->prepare($profile, $mode);
		$user = $request->user();

		if (!empty($user)) {
			$profile->ankVisits	= $this->anketVisitRepository->update($id, config('profile.visit_days'), $user->id);
			$evaluationed = $this->anketEvaluationRepository->getEvaluationWithUpdate($request, $user->id, $id);
		}
		return [
			'userData' => $profile,
			'evaluationed' => isset($evaluationed) ? $evaluationed : false
			];
	}

	/**
	 * Update main profile data 
	 */
	public function updateMain(User $user, UpdateMainProfileDTO $dto): void
	{
		$this->update($user, $dto->toArray());
	}

	/**
	 * Update full profile data 
	 */
	public function updateSecond(User $user, UpdateSecondProfileDTO $dto): void
	{
		$this->update($user, $dto->toArray());
	}

	/**
	 * Update partner profile data 
	 */
	public function updatePartner(User $user, UpdatePartnerProfileDTO $dto): void
	{
		$this->update($user, $dto->toArray());
	}	

	/**
	 * Update a profile using data 
	 */
	private function update(User $user, array $data)
	{
		$this->repository->update($user, $data);
	}
}