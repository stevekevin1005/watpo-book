// 導覽列

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
              <span onClick={this.props.toIndex} style={{cursor: "pointer"}}>泰和殿 Wat po</span>
            </Navbar.Brand>
            <Navbar.Toggle />
          </Navbar.Header>
          <Navbar.Collapse>
            <Nav_>
              <NavItem onClick={()=>{this.props.toReservation();}}>
                預約服務
              </NavItem>
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