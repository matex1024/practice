<?php

namespace App\Tests\Filter;

use App\Filter\SearchOrFilter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SearchOrFilterTest extends TestCase
{
    private QueryBuilder $queryBuilder;
    private QueryNameGeneratorInterface $queryNameGenerator;
    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->queryNameGenerator = $this->createMock(QueryNameGeneratorInterface::class);
        $this->requestStack = new RequestStack();

        $request = new Request(['title' => 'test']);
        $this->requestStack->push($request);

        $this->queryBuilder->method('getRootAliases')
            ->willReturn(['t']);

        $this->queryBuilder->method('expr')
            ->willReturn(new Expr());

        $this->queryBuilder->method('andWhere')
            ->willReturnSelf();
    }

    public function testApplyPartial(): void
    {
        $filter = new SearchOrFilter($this->requestStack, ['title' => 'partial']);

        $this->queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with($this->anything(), '%test%');

        $filter->apply($this->queryBuilder, $this->queryNameGenerator, 'App\Entity\YourEntity');
    }

    public function testApplyExact(): void
    {
        $filter = new SearchOrFilter($this->requestStack, ['title' => 'exact']);

        $this->queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with($this->anything(), 'test');

        $filter->apply($this->queryBuilder, $this->queryNameGenerator, 'App\Entity\YourEntity');
    }

    public function testApplyIPartial(): void
    {
        $filter = new SearchOrFilter($this->requestStack, ['title' => 'ipartial']);

        $this->queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with($this->anything(), '%test%');

        $filter->apply($this->queryBuilder, $this->queryNameGenerator, 'App\Entity\YourEntity');
    }

    public function testApplyIExact(): void
    {
        $filter = new SearchOrFilter($this->requestStack, ['title' => 'iexact']);

        $this->queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with($this->anything(), 'test');

        $filter->apply($this->queryBuilder, $this->queryNameGenerator, 'App\Entity\YourEntity');
    }
}
