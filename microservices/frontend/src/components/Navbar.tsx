import React from 'react';
import {
  AppBar,
  Toolbar,
  Typography,
  Button,
  IconButton,
  Badge,
  Box,
  Menu,
  MenuItem,
} from '@mui/material';
import {
  ShoppingCart,
  AccountCircle,
  Store,
  Home,
} from '@mui/icons-material';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const Navbar: React.FC = () => {
  const navigate = useNavigate();
  const { user, isAuthenticated, logout } = useAuth();
  const [anchorEl, setAnchorEl] = React.useState<null | HTMLElement>(null);

  const handleMenu = (event: React.MouseEvent<HTMLElement>) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const handleLogout = () => {
    logout();
    handleClose();
    navigate('/');
  };

  return (
    <AppBar position="static">
      <Toolbar>
        <Typography
          variant="h6"
          component="div"
          sx={{ cursor: 'pointer' }}
          onClick={() => navigate('/')}
        >
          <Store sx={{ mr: 1, verticalAlign: 'middle' }} />
          E-Commerce
        </Typography>

        <Box sx={{ flexGrow: 1, ml: 4 }}>
          <Button
            color="inherit"
            startIcon={<Home />}
            onClick={() => navigate('/')}
          >
            Inicio
          </Button>
          <Button
            color="inherit"
            onClick={() => navigate('/products')}
          >
            Productos
          </Button>
        </Box>

        <Box sx={{ display: 'flex', alignItems: 'center' }}>
          {isAuthenticated ? (
            <>
              <IconButton
                color="inherit"
                onClick={() => navigate('/cart')}
              >
                <Badge badgeContent={0} color="secondary">
                  <ShoppingCart />
                </Badge>
              </IconButton>

              <IconButton
                size="large"
                aria-label="account of current user"
                aria-controls="menu-appbar"
                aria-haspopup="true"
                onClick={handleMenu}
                color="inherit"
              >
                <AccountCircle />
              </IconButton>
              <Menu
                id="menu-appbar"
                anchorEl={anchorEl}
                anchorOrigin={{
                  vertical: 'top',
                  horizontal: 'right',
                }}
                keepMounted
                transformOrigin={{
                  vertical: 'top',
                  horizontal: 'right',
                }}
                open={Boolean(anchorEl)}
                onClose={handleClose}
              >
                <MenuItem onClick={() => { navigate('/profile'); handleClose(); }}>
                  Perfil
                </MenuItem>
                <MenuItem onClick={() => { navigate('/orders'); handleClose(); }}>
                  Mis Pedidos
                </MenuItem>
                <MenuItem onClick={handleLogout}>
                  Cerrar Sesión
                </MenuItem>
              </Menu>
            </>
          ) : (
            <>
              <Button color="inherit" onClick={() => navigate('/login')}>
                Iniciar Sesión
              </Button>
              <Button color="inherit" onClick={() => navigate('/register')}>
                Registrarse
              </Button>
            </>
          )}
        </Box>
      </Toolbar>
    </AppBar>
  );
};

export default Navbar;
