<?php

namespace Src\coupon\infrastructure\controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Src\coupon\application\use_cases\CreateCouponUseCase;
use Src\coupon\application\use_cases\DeleteCouponUseCase;
use Src\coupon\application\use_cases\FindAllCouponsUseCase;
use Src\coupon\application\use_cases\UpdateCouponUseCase;
use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\exceptions\InvalidCouponAmountException;
use Src\coupon\domain\exceptions\InvalidCouponPercentageException;
use Src\coupon\domain\value_objects\CouponCode;
use Src\coupon\domain\value_objects\CouponType;

use Src\shared\domain\exception\InvalidPermission;
use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Currency;
use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class CouponController extends Controller {

    public function __construct(
        private CreateCouponUseCase $createCouponUseCase,
        private UpdateCouponUseCase $updateCouponUseCase,
        private DeleteCouponUseCase $deleteCouponUseCase,
        private FindAllCouponsUseCase $findAllCouponsUseCase,
    ) {}


    public function index(Request $request) {

        $data = $this->findAllCouponsUseCase->execute($request->all());

        return $this->resApi(
            data: $data,
            status: Response::HTTP_OK
       );
    }

    public function update(Request $request, string $code) {
        $data = $request->validate([
            'type' => 'required|string|in:percent,fixed',
            'amount' => 'nullable|numeric',
            'currency_symbol' => 'nullable|string|required_without:percent',
            'percent' => 'nullable|numeric',
            'expires_at' => 'required|date:Y-m-d H:i:s',
            'is_active' => 'required|boolean',
            'category_id' => 'nullable|string|exists:categories,id',
        ]);

        try {
            $coupon = new Coupon(
                new CouponCode($code),
                new CouponType($data['type']),
                $request->get("amount")? new Amount(
                    $data['amount'],
                    new Currency("$")
                ): null,
                $request->get('percent', null),
                new \DateTime($data['expires_at']),
                $data['is_active'],
                $request->get("category_id", null)? new Id($data['category_id']): null
            );

            $this->updateCouponUseCase->execute($coupon);

            return $this->resApi(
                data: $this->mapDomainToArray($coupon),
                status: Response::HTTP_OK
            );
        } catch(InvalidPermission $e){
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        } catch(InvalidCouponPercentageException $e){
            return $this->resApi(
                message: $e->getMessage(),
                errors: ['percent' => $e->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        } catch(InvalidCouponAmountException $e){
            return $this->resApi(
                message: $e->getMessage(),
                errors: ['amount' => $e->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        } catch(DataNotFoundException $e){
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_NOT_FOUND
            );
        }
    }

    public function store(Request $request) {
        $data = $request->validate([
            'type' => 'required|string|in:percent,fixed',
            'amount' => 'nullable|numeric|min:1',
            'currency_symbol' => 'nullable|string|required_with:amount',
            'percent' => 'nullable|integer|min:0|max:90',
            'expires_at' => 'required|date:Y-m-d H:i:s',
            'is_active' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        try {
            $coupon = new Coupon(
                CouponCode::generateCode(),
                new CouponType($data['type']),
                $request->get("amount")? new Amount(
                    $data['amount'],
                    new Currency("$")
                ): null,
                $request->get('percent', null),
                new \DateTime($data['expires_at']),
                $data['is_active'],
                $request->get("category_id", null)? new Id($data['category_id']): null
            );

            $this->createCouponUseCase->execute($coupon);

            return $this->resApi(
                data: $this->mapDomainToArray($coupon),
                status: Response::HTTP_CREATED
            );
        } catch(InvalidPermission $e){
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        } catch(InvalidCouponPercentageException $e){
            return $this->resApi(
                message: $e->getMessage(),
                errors: ['percent' => $e->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        } catch(InvalidCouponAmountException $e){
            return $this->resApi(
                message: $e->getMessage(),
                errors: ['amount' => $e->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function destroy(string $code) {
        try {
            $this->deleteCouponUseCase->execute(new CouponCode($code));

            return $this->resApi(
                data: null,
                status: Response::HTTP_NO_CONTENT
            );
        } catch(InvalidPermission $e){

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        } catch(DataNotFoundException $e){
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_NOT_FOUND
            );
        }
    }

    private function mapDomainToArray(Coupon $coupon) {

        return [
            'code' => $coupon->code(),
            'type' => $coupon->type(),
            'amount' => $coupon->amount(),
            'percent' => $coupon->percent(),
            'expires_at' => $coupon->expiresAt(),
            'is_active' => $coupon->isValidNow(),
            'category_id' => $coupon->category_id(),
        ];
    }
}
