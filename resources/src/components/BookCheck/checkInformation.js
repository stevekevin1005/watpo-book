import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import Button from "./Button";

const Grid = ReactBootstrap.Grid,
        Row = ReactBootstrap.Row,
        Col = ReactBootstrap.Col,
        FormGroup = ReactBootstrap.FormGroup,
        FormControl = ReactBootstrap.FormControl,
        ControlLabel = ReactBootstrap.ControlLabel;


class CheckInformation extends React.Component{
    constructor(props){
        super(props);
    }
    componentDidMount(){
       
        const sourceData = this.props.sourceData;

        const that = this,
              csrf_token = document.querySelector('input[name="_token"]').value;
        let finished = 0;
    }

    render(){
        const { t } = this.props, sourceData = this.props.sourceData;
        var disabled = false;
        return(
            <Grid>
                <Row>
                    <Col md={7}>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel bsClass="control-label branch">{t("reservatorName")}</ControlLabel>
                            <FormControl
                                type="text"
                            />
                        </FormGroup>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel>{t("contactNumber")}</ControlLabel>
                            <FormControl
                                type="text"
                            />
                        </FormGroup>
                    </Col>
                    <Button currentStep={0} clickHandle={this.props.nextStep} disabled={disabled}/>
                </Row>
            </Grid>
        );
    }
}

const mapStateToProps = (state)=>{
    return {
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
       
    },dispatch);
}

CheckInformation = connect(mapStateToProps,mapDispatchToProps)(CheckInformation);

module.exports = translate()(CheckInformation);