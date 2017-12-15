// 導覽列

import { LinkContainer } from 'react-router-bootstrap';

const Navbar = ReactBootstrap.Navbar,
Nav_ = ReactBootstrap.Nav,
NavItem = ReactBootstrap.NavItem,
NavDropdown = ReactBootstrap.NavDropdown,
MenuItem = ReactBootstrap.MenuItem;

class Nav extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        return(
          <Navbar inverse collapseOnSelect>
          <Navbar.Header>
            <Navbar.Brand>
              <LinkContainer to="/">
                <span style={{cursor: "pointer"}}>泰和殿 Wat po</span>
              </LinkContainer>
            </Navbar.Brand>
            <Navbar.Toggle />
          </Navbar.Header>
          <Navbar.Collapse>
            <Nav_>
              <LinkContainer to="/reservation/0">
                <NavItem>
                  預約服務
                </NavItem>
              </LinkContainer>
            </Nav_>
            <Nav_ pullRight>
              <NavDropdown title="語言" id="basic-nav-dropdown">
                <MenuItem >中文</MenuItem>
                <MenuItem >日本語</MenuItem>
                <MenuItem >English</MenuItem>
              </NavDropdown>
            </Nav_>
          </Navbar.Collapse>
        </Navbar>
        );
    }
}

module.exports = Nav;