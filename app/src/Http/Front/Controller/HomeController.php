<?php

namespace Http\Front\Controller;

use Core\Application\Controller;
use Http\Front\ViewModel\HomeViewModel;

/**
 * Class HomeController
 * @package App\Front\Controller
 */
class HomeController extends Controller
{

    /**
     * @return array
     */
    public function indexAction(HomeViewModel $viewModel)
    {
        return $this->render($viewModel);
    }
}