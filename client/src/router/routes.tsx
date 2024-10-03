
import { createBrowserRouter } from 'react-router-dom';

import Home from '@/pages/home';
import Product from '@/pages/product';

export const router = createBrowserRouter([
    {
        path: "/",
        element: <Home />,
    },
    {
        path: "/product/:product_id",
        element: <Product />
    },
]);