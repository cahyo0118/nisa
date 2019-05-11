<?php

namespace App\Http\Middleware;

use Closure;
use App\Vote;
use App\VoteToken;

class AuthVoteToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        error_log($request->vote_token);
        $vote_token = VoteToken::where('token', $request->vote_token)->first();

        if (empty($vote_token)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Vote token not found !' . $request->header('vote_token'),
            ], 400);
        }

        $vote = $vote_token->vote();

        if (empty($vote)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return $next($request);
    }
}
