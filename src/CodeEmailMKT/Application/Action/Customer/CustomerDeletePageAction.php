<?php

namespace CodeEmailMKT\Application\Action\Customer;

use CodeEmailMKT\Domain\Entity\Customer;
//use Symfony\Component\Routing\RouterInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template;
use CodeEmailMKT\Domain\Persistence\CustomerRepositoryInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class CustomerDeletePageAction
{
    private $template;
    /**
     * @var CustomerRepositoryInterface
     */
    private $repository;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(CustomerRepositoryInterface $repository,
                                TemplateRendererInterface $template,
                                RouterInterface $router)
    {
        $this->template = $template;
        $this->repository = $repository;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $id = $request->getAttribute('id');
        $entity = $this->repository->find($id);

        if($request->getMethod() == 'DELETE'){
            $flash = $request->getAttribute('flash');
            $this->repository->remove($entity);
            $flash->setMessage('success','Contato removido com sucesso');
            $uri = $this->router->generateUri('customer.list');
            return new RedirectResponse($uri);
        }
        return new HtmlResponse($this->template->render("app::customer/delete",[
            'customer' => $entity
        ]));
    }
}
