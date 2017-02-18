<?php

namespace voskobovich\api\filters\auth;

/**
 * Class QueryParamAuth.
 */
class QueryParamAuth extends \yii\filters\auth\QueryParamAuth
{
    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->get($this->tokenParam);
        if (!is_string($accessToken)) {
            $this->handleFailure($response);
        }

        $identity = $user->loginByAccessToken($accessToken, get_class($this));
        if ($identity !== null) {
            return $identity;
        }

        return null;
    }
}
