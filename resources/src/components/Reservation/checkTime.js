// 負責寫資料(日期,時段)到global state

const Grid = ReactBootstrap.Grid,
Row = ReactBootstrap.Row,
Col = ReactBootstrap.Col,
ListGroupItem = ReactBootstrap.ListGroupItem,
ListGroup = ReactBootstrap.ListGroup;

class CheckTime extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        return(
            <Grid>
            <Row className="show-grid">
            <Col md={5}>
                <div style={{backgroundColor: "#F5F5F5",borderRadius:"16px",border:"solid 1px #E8E8E8", padding: "8px 16px", height: "300px"}}>
                    日曆
                </div>
            </Col>
            <Col md={5}>
                <div style={{height: "300px", overflowY:"auto"}}>
                    <ListGroup>
                        <ListGroupItem>Item 1</ListGroupItem>
                        <ListGroupItem>Item 2</ListGroupItem>
                        <ListGroupItem>...</ListGroupItem>
                        <ListGroupItem>...</ListGroupItem>
                        <ListGroupItem>...</ListGroupItem>
                        <ListGroupItem>...</ListGroupItem>
                        <ListGroupItem>...</ListGroupItem>
                        <ListGroupItem>...</ListGroupItem>
                    </ListGroup>
                </div>
            </Col>
            </Row>
            </Grid>
        );
    }
}

module.exports = CheckTime;