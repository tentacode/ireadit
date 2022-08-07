<?php

declare(strict_types=1);

namespace spec\App\Brick\Link\Query;

use App\Brick\Link\Query\FindLinksToReadByUser;
use App\Brick\Poulpe\SqlQuery;
use PhpSpec\ObjectBehavior;

class FindLinksToReadByUserSpec extends ObjectBehavior
{
    public function let(SqlQuery $sqlQuery)
    {
        $this->beConstructedWith($sqlQuery);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FindLinksToReadByUser::class);
    }
}
