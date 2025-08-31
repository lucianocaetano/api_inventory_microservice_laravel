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
use Src\coupon\domain\value_objects\CouponCode;
use Src\coupon\domain\value_objects\CouponType;
use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Currency;

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
            'currency_code' => 'required|string',
            'currency_symbol' => 'required|string',
            'currency_decimals' => 'required|integer',
            'percent' => 'nullable|numeric',
            'expires_at' => 'required|date',
            'is_active' => 'required|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $coupon = new Coupon(
            new CouponCode($code),
            new CouponType($data['type']),
            new Amount(
                $data['amount'],
                new Currency($data['currency_code'], $data['currency_symbol'], $data['currency_decimals'])
            ),
            $data['percent'],
            new \DateTime($data['expires_at']),
            $data['is_active'],
            $data['category_id']
        );

        $data = $this->updateCouponUseCase->execute($coupon);

        return $this->resApi(
            data: $this->mapDomainToArray($data),
            status: Response::HTTP_OK
       );
    }
    public function store(Request $request) {

        $data = $request->validate([
            'type' => 'required|string|in:percent,fixed',
            'amount' => 'nullable|numeric',
            'currency_code' => 'required|string',
            'currency_symbol' => 'required|string',
            'currency_decimals' => 'required|integer',
            'percent' => 'nullable|numeric',
            'expires_at' => 'required|date',
            'is_active' => 'required|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $coupon = new Coupon(
            CouponCode::generateCode(),
            new CouponType($data['type']),
            new Amount(
                $data['amount'],
                new Currency($data['currency_code'], $data['currency_symbol'], $data['currency_decimals'])
            ),
            $data['percent'],
            new \DateTime($data['expires_at']),
            $data['is_active'],
            $data['category_id']
        );

        $this->createCouponUseCase->execute($coupon);

        return $this->resApi(
            data: $this->mapDomainToArray($coupon),
            status: Response::HTTP_CREATED
        );
    }

    public function destroy(string $code) {
        $this->deleteCouponUseCase->execute(new CouponCode($code));

        return $this->resApi(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
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
