import { useState } from 'react';
import { useQuery } from 'react-query';
// components
import Drawer from '@material-ui/core/Drawer';
import LinearProgress from '@material-ui/core/LinearProgress';
import Grid from '@material-ui/core/Grid';
import AddShoppingCartIcon from '@material-ui/icons/AddShoppingCart';
import Badge from '@material-ui/core/Badge';
import Item from './Item/Item';
import Cart from './Cart/Cart';
// styles
import { Wrapper, StyledButton } from './App.style';
// Types
import { CartItemType } from './Types/CartItemType';
import AppToolBar from './AppToolBar/AppToolBar';

const getProducts = async (): Promise<CartItemType[]> => {
  return await (
    await fetch('https://fakestoreapi.herokuapp.com/products')
  ).json();
};

const App = () => {
  const [cartOpen, setCartOpen] = useState(false);
  const [cartItems, setCartItems] = useState([] as CartItemType[]);
  const { isLoading, error, data } = useQuery<CartItemType[]>(
    'products',
    getProducts
  );
  const getTotalItems = (items: CartItemType[]) => {
    return items.reduce((ack: number, item) => ack + item.amount, 0);
  };
  const handleAddToCart = (clickedItem: CartItemType) => {
    setCartItems((prev) => {
      // 1. is the item already exist added in the cart ?
      const isItemIncart = prev.find((item) => item.id === clickedItem.id);
      if (isItemIncart) {
        return prev.map((item) =>
          item.id === clickedItem.id
            ? { ...item, amount: item.amount + 1 }
            : item
        );
      }
      // first time the item is added
      return [...prev, { ...clickedItem, amount: 1 }];
    });
  };
  const handleRemoveFromCart = (id: number) => {
    setCartItems(prev =>
        prev.reduce((ack, item) => {
          if (item.id === id) {
            if(item.amount===1) return ack;
            return [...ack, {...item, amount:item.amount-1}]
          }else{
            return [...ack, item]
          }
        },
        [] as CartItemType[])
      )
    ;
  };
  if (isLoading) return <LinearProgress color="primary" />;
  if (error) return <p>Error {error}</p>;

  return (
    <Wrapper>
      <AppToolBar />
      <Drawer anchor="right" open={cartOpen} onClose={() => setCartOpen(false)}>
        <Cart
          cartItems={cartItems}
          addToCart={handleAddToCart}
          removeFromCart={handleRemoveFromCart}
        />
      </Drawer>
      <StyledButton onClick={() => setCartOpen(true)}>
        <Badge badgeContent={getTotalItems(cartItems)} color="default">
          <AddShoppingCartIcon fontSize="large" />
        </Badge>
      </StyledButton>
      <Grid style={{ padding: '0.5rem' }} container spacing={4}>
        {data?.map((item) => (
          <Grid item key={item.id} xs={3} md={4} sm={3}>
            <Item item={item} handleAddToCart={handleAddToCart} />
          </Grid>
        ))}
      </Grid>
    </Wrapper>
  );
};

export default App;
