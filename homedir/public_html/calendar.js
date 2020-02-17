// Title: Tigra Calendar
// URL: http://www.softcomplex.com/products/tigra_calendar/
// Version: 3.2 (European date format)
// Date: 10/14/2002 (mm/dd/yyyy)
// Feedback: feedback@softcomplex.com (specify product title in the subject)
// Note: Permission given to use this script in ANY kind of applications if
//    header lines are left unchanged.
// Note: Script consists of two files: calendar?.js and calendar.html
// About us: Our company provides offshore IT consulting services.
//    Contact us at sales@softcomplex.com if you have any programming task you
//    want to be handled by professionals. Our typical hourly rate is $20.

// if two digit year input dates after this year considered 20 century.
var NUM_CENTYEAR = 30;
// is time input control required by default
var BUL_TIMECOMPONENT = false;
// are year scrolling buttons required by default
var BUL_YEARSCROLL = true;

var calendars = [];
var RE_NUM = /^\-?\d+$/;

function calendar(obj_target, year_name, month_name, day_name) {

        // assing methods
        this.prs_date = cal_prs_date;
        this.prs_tsmp = cal_prs_tsmp;
        this.from_select = from_select;
        this.popup    = cal_popup;
        this.set_date    = set_date;

        this.year_name = year_name;
        this.month_name = month_name;
        this.day_name = day_name;

        // validate input parameters
        if (!obj_target)
                return cal_error("Error calling the calendar: no target control specified");

        this.target = obj_target;
        this.time_comp = BUL_TIMECOMPONENT;
        this.year_scroll = BUL_YEARSCROLL;

        // register in global collections
        this.id = calendars.length;
        calendars[this.id] = this;
}

//set date in select
function set_date(str_datetime) {
    cur_date = new Date(str_datetime);
    all_year = this.target.elements[this.year_name];
    this.year = cur_date.getFullYear();
//    alert(this.year);
    for(i = 0; i < all_year.length; i++) {
//        alert(all_year[i].value);
        if (all_year[i].value == this.year) {
            all_year[i].selected = true;
            break;
        }
    }
    all_month = this.target.elements[this.month_name];
    this.month = cur_date.getMonth() + 1;
    for(i = 0; i < all_month.length; i++) {
        if (all_month[i].value == this.month) {
            all_month[i].selected = true;
        }
    }
    all_day = this.target.elements[this.day_name];
    this.day = cur_date.getDate();
    for(i = 0; i < all_day.length; i++) {
        if (all_day[i].value == this.day) {
            all_day[i].selected = true;
        }
    }
}

function cal_popup (str_datetime) {

        if (!str_datetime) {
            this.dt_current = new Date();
        }
        else {
            this.dt_current = new Date(str_datetime);
        }
        if (!this.dt_current) return;
//        alert(const_link);
        var obj_calwindow = window.open(
                const_link + '/calendar.php?datetime=' + this.dt_current.valueOf()+ '&id=' + this.id,
                'Calendar', 'width=200,height='+(this.time_comp ? 215 : 190)+
                ',status=no,resizable=no,top=200,left=200,dependent=yes,alwaysRaised=yes'
        );
        obj_calwindow.opener = window;
        obj_calwindow.focus();
}

//generate date from 3 select boxes
function from_select() {
        this.year = this.target.elements[this.year_name].value;
        this.month = this.target.elements[this.month_name].value;
        this.day = this.target.elements[this.day_name].value;
//        alert(this.year);
        var cur_date = this.prs_tsmp();
//        alert(cur_date);
        if (cur_date) {
            this.popup(cur_date.valueOf());
        }
}

// timestamp parsing function
function cal_prs_tsmp () {
        // if no parameter specified return current timestamp
        if (this.year === "" || this.month === "" || this.day === "") {
            return (new Date());
        }
        else {
            return this.prs_date();
        }
}

// date parsing function
function cal_prs_date() {
//        alert('bbb');
        var dt_date = new Date();

        dt_date.setMonth(this.month-1);

        dt_date.setFullYear(this.year);

        dt_date.setDate(this.day);
//        alert(dt_date.getMonth() +"!="+ (this.month-1));
        if (dt_date.getMonth() != (this.month-1)) {
            var dt_numdays = new Date(this.year, this.month, 0);
            return cal_error ("Invalid day of this month value.\nAllowed range is 01-"+dt_numdays.getDate()+".");
        }

        return (dt_date)
}

function cal_error (str_message) {
        alert (str_message);
        return null;
}
