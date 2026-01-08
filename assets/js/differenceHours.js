var DifferenceHours = function(options){

    /*
     * Variables
     * in the class
     */
    const vars = {
        first_hour_split: null,
        second_hour_split: null,
        $el: null
    };

    /*
     * Can access this.method
     * inside other methods using
     * _this.method()
     */
    let _this = this;

    /*
     * Constructor
     */
    this.construct = function (options) {
        $.extend(vars , options);
    };

    /*
     * PUBLIC
     */

    this.diff_hours = function (time1, time2, gross_time) {
        ////console.log('time2++++++@@'+$('#' + time2).val());
        vars.first_ampm_split = $('#' + time1).val().split(' ');
        vars.second_ampm_split = $('#' + time2).val().split(' ');
        vars.first_hour_split = $('#' + time1).val().split(':');
        vars.second_hour_split = $('#' + time2).val().split(':');
        vars.$el = $('#' + gross_time);

        let hours;
        let minute;
        //let brk_time = parseInt(vars.brk_time1)+parseInt(vars.brk_time2)+parseInt(vars.brk_time3);
        
        if(vars.first_ampm_split[1] == 'AM'){
            ////console.log('---');
            if(vars.first_hour_split[0] == 12){
                ////console.log('+++');
                vars.first_hour_split[0] = 0;
            }
        }
        else{
            ////console.log('##');
            if(vars.first_hour_split[0] == 12){
                vars.first_hour_split[0] = vars.first_hour_split[0];
            }
            else{
                vars.first_hour_split[0] = 12+parseInt(vars.first_hour_split[0]);
            }
            
        }

        if(vars.second_ampm_split[1] == 'AM'){
            if(vars.second_hour_split[0] == 12){
                vars.second_hour_split[0] = 0;
            }
        }
        else{
            if(vars.second_hour_split[0] == 12){
                vars.second_hour_split[0] = vars.second_hour_split[0];
            }
            else{
                vars.second_hour_split[0] = 12+parseInt(vars.second_hour_split[0]);
            }
        }

        ////console.log('1st hour--'+vars.first_hour_split[0]);
        ////console.log('2nd hour--'+vars.second_hour_split[0]);

        /*if(isNaN(vars.first_hour_split[0]) == true){
            //console.log('1st time hour NaN');
            vars.first_hour_split[0] = 0;
        }

        if(isNaN(vars.first_hour_split[1]) == true){
            //console.log('1st time min NaN');
            vars.first_hour_split[1] = 0;
        }

        if(isNaN(vars.second_hour_split[0]) == true){
            //console.log('2nd time hour NaN');
            vars.second_hour_split[0] = 0;
        }
        if(isNaN(vars.second_hour_split[1]) == true){
            //console.log('2nd time min NaN');
            vars.second_hour_split[1] = 0;
        }*/

        ////console.log('1st time--'+vars.first_hour_split[0]+'**'+vars.first_hour_split[1]);
        ////console.log('2nd time--'+vars.second_hour_split[0]+'**'+vars.second_hour_split[1]);

        if (parseInt(vars.first_hour_split[0]) < parseInt(vars.second_hour_split[0]) && parseInt(vars.first_hour_split[1]) < parseInt(vars.second_hour_split[1])) {
            ////console.log('1if');
            //As for the addition, the subtraction is carried out separately, column by column.
            hours = parseInt(vars.second_hour_split[0]) - parseInt(vars.first_hour_split[0]);
            minute = parseInt(vars.second_hour_split[1]) - parseInt(vars.first_hour_split[1]);

            let _hours = '';
            let _minute = '';
            let _brkmin = '';

            if (hours < 10) {
                _hours ='0' + hours;
            } else {
                _hours = hours;
            }

            if (minute < 10) {
                _minute = '0' + minute;
            } else {
                _minute = minute;
            }

            if(isNaN(_hours) == true){
                ////console.log('_hours NaN');
                _hours = 0;
            }

            if(isNaN(_minute) == true){
                ////console.log('_minute');
                _minute = 0;
            }

            vars.$el.val(_hours + ':' + _minute)

        }else if (parseInt(vars.second_hour_split[0]) > parseInt(vars.first_hour_split[0])) {
            ////console.log('else if 2');
            if (parseInt(vars.second_hour_split[1]) < parseInt(vars.first_hour_split[1])) {
                ////console.log('if 2');
                // As before we subtract column by column ... and we realize that it's impossible because our minute in second hour is greater than our minute in first hour
                // We will transform 1 hour in 60 minutes
                let _hours = parseInt(vars.second_hour_split[0]) - 1;
                let _minute = parseInt(vars.second_hour_split[1]) + 60;
                let final_hours = '';
                let final_min = '';
                let _brkmin = ''; 

                hours = _hours - parseInt(vars.first_hour_split[0]);
                minute = _minute - parseInt(vars.first_hour_split[1]);

                if (hours < 10) {
                    final_hours = '0' + hours;
                } else {
                    final_hours = hours;
                }

                if (minute < 10) {
                    final_min = '0' + minute;
                } else {
                    final_min = minute;
                }

                vars.$el.val(final_hours + ':' + final_min)
            }

            if (parseInt(vars.second_hour_split[1]) === parseInt(vars.first_hour_split[1])) {
                hours = parseInt(vars.second_hour_split[0]) - parseInt(vars.first_hour_split[0]);
                ////console.log('3');
                let final_hours = '';
                let _brkmin = ''; 
                let final_min = 0;

                if (hours < 10) {
                    final_hours = '0' + hours;
                } else {
                    final_hours = hours;
                }

                if(isNaN(final_hours) == true){
                ////console.log('final_hours NaN');
                    final_hours = 0;
                }

                if(isNaN(final_min) == true){
                    ////console.log('final_min');
                    final_min = 0;
                }

                vars.$el.val(final_hours + ':' + final_min)
            }

        }else if (parseInt(vars.first_hour_split[0]) > parseInt(vars.second_hour_split[0])) {
            ////console.log('else if 4');
            let first_hour_only_hour = parseInt(vars.first_hour_split[0]);
            let second_hour_only_hour = parseInt(vars.second_hour_split[0]);

            let first_hour_only_min = parseInt(vars.first_hour_split[1]);
            let second_hour_only_min = parseInt(vars.second_hour_split[1]);

            ////console.log('first_hour_only_hour---'+first_hour_only_hour);
            ////console.log('second_hour_only_hour---'+second_hour_only_hour);

            let tmp_hour = 24 - first_hour_only_hour; 
            let tmp_ttl_hour = tmp_hour + second_hour_only_hour; 

            let tmp_ttl_min = first_hour_only_min + second_hour_only_min;
            let tmp_new_hour = 0;
            let tmp_new_min_mod = 0;

            let _hours = '';
            let _min = '';

            let _brkmin = '';

            /*if(second_hour_only_min > first_hour_only_min){
                _min = second_hour_only_min - first_hour_only_min;
            }
            if(second_hour_only_min == '00' || second_hour_only_min == '0'){
                second_hour_only_min = 60;
                _min = second_hour_only_min - first_hour_only_min;
                _hours = tmp_ttl_hour - 1;
            }*/

            ////console.log('tmp_ttl_min:'+tmp_ttl_min);
            ////console.log('tmp_ttl_hour---:'+tmp_ttl_hour);

            if (tmp_ttl_min > 59) {
                tmp_new_hour = parseInt(tmp_ttl_min/60);
                //tmp_new_min_mod = tmp_ttl_min%60;
                ////console.log('tmp_new_hour:'+tmp_new_hour);
                ////console.log('tmp_ttl_hour---:'+tmp_ttl_hour);
                //tmp_ttl_hour += tmp_new_hour;

                if(second_hour_only_min > first_hour_only_min || second_hour_only_min == first_hour_only_min){
                    _min = second_hour_only_min - first_hour_only_min;
                    _hours = tmp_ttl_hour
                }
    
            } else {
                ////console.log('else:');
               /* if(second_hour_only_min == '00' || second_hour_only_min == '0'){
                    //console.log('second_hour_only_min 00:');
                    //second_hour_only_min = 60;
                    _min = second_hour_only_min - first_hour_only_min;
                    _hours = tmp_ttl_hour;
                }
                else */
                if(second_hour_only_min > first_hour_only_min || second_hour_only_min == first_hour_only_min){
                    ////console.log('second_hour_only_min > ==:');
                    /*if(first_hour_only_hour > second_hour_only_hour){
                        //console.log('+++if+++');
                        _min = second_hour_only_min + first_hour_only_min;
                        temp = 24 + second_hour_only_hour;
                        //console.log('temp'+temp+'first_hour_only_hour'+first_hour_only_hour);
                        _hours = temp - first_hour_only_hour;
                    }
                    else{*/
                        ////console.log('+++else+++');
                        _min = second_hour_only_min - first_hour_only_min;
                        _hours = tmp_ttl_hour
                    //}
                }
                else if(second_hour_only_min < first_hour_only_min){
                    ////console.log('second_hour_only_min < ==:');
                    _min = second_hour_only_min + first_hour_only_min;
                    if(second_hour_only_min == 0){
                        _min = 60 - (second_hour_only_min + first_hour_only_min);
                    }
                    temp = 24 + second_hour_only_hour;
                    _hours = temp - first_hour_only_hour -1;
                }
            }

            if(isNaN(_hours) == true){
                ////console.log('_hours NaN');
                _hours = 0;
            }

            if(isNaN(_min) == true){
                ////console.log('_min');
                _min = 0;
            }
            vars.$el.val(_hours + ':' + _min)

        } else if (parseInt(vars.first_hour_split[0]) === parseInt(vars.second_hour_split[0])) {
            ////console.log('5');
            hours = '0';
            let minute = 0;
            let _brkmin = '';
            if (parseInt(vars.first_hour_split[1]) < parseInt(vars.second_hour_split[1])) {
                minute = parseInt(vars.second_hour_split[1]) - parseInt(vars.first_hour_split[1]);
            }

            if(isNaN(hours) == true){
                ////console.log('hours NaN');
                hours = 0;
            }

            if(isNaN(minute) == true){
                ////console.log('minute');
                minute = 0;
            }

            vars.$el.val(hours + ':' + minute)

            // if (minute < 10) {
            //     vars.$el.val(hours + ':' + minute)
            // } else  {
            //     vars.$el.val(hours + ':' + minute)
            // }
        }else if (parseInt(vars.first_hour_split[0]) === 0 && parseInt(vars.first_hour_split[1]) === 0) {
            hours = parseInt(vars.second_hour_split[0]);
            minute = parseInt(vars.second_hour_split[1]);

            let _brkmin = '';
            ////console.log('6');
            if (hours === 0) {
            
                if(isNaN(minute) == true){
                    ////console.log('minute');
                    minute = 0;
                }

                vars.$el.val('00:' + minute)

            }else if (minute === 0){
                if(isNaN(hours) == true){
                    ////console.log('hours NaN');
                    hours = 0;
                }

                if (hours < 10) {
                    vars.$el.val('0' + hours + ':00');
                }else {
                    vars.$el.val(hours + ':00');
                }
            }else {
                
                if(isNaN(hours) == true){
                    ////console.log('hours NaN');
                    hours = 0;
                }

                if(isNaN(minute) == true){
                    ////console.log('minute');
                    minute = 0;
                }

                vars.$el.val(hours + ':' + minute)
            }
        }
    };

    this.net_diff_hours = function (time1, time2, brk_time1, brk_time2, brk_time3, net_time, total_brk_time) {
        vars.first_ampm_split = $('#' + time1).val().split(' ');
        vars.second_ampm_split = $('#' + time2).val().split(' ');
        vars.first_hour_split = $('#' + time1).val().split(':');
        vars.second_hour_split = $('#' + time2).val().split(':');
        vars.brk_time1 = $('#' + brk_time1).val();
        vars.brk_time2 = $('#' + brk_time2).val();
        vars.brk_time3 = $('#' + brk_time3).val();
        vars.$total_brk_time = $('#total_brk_time');
        vars.$el = $('#net_time');
        
        let hours;
        let minute;
        let brk_time = 0;

        /*if(vars.brk_time1 == '' && vars.brk_time1 == null && vars.brk_time1 == 'null'){
            vars.brk_time1 = 0;
        }

        if(vars.brk_time2 == '' && vars.brk_time2 == null && vars.brk_time2 == 'null'){
            vars.brk_time2 = 0;
        }

        if(vars.brk_time3 == '' && vars.brk_time3 == null && vars.brk_time3 == 'null'){
            vars.brk_time3 = 0;
        }
*/
        //brk_time = brk_time + parseInt(vars.brk_time1) + parseInt(vars.brk_time2) + parseInt(vars.brk_time3);

        if(vars.brk_time1 != '' && vars.brk_time1 != null && vars.brk_time1 != 'null'){
            brk_time = brk_time + parseInt(vars.brk_time1);
        }
        
        //console.log('vars.brk_time-->'+brk_time);
        if(vars.brk_time2 != '' && vars.brk_time2 != null && vars.brk_time2 != 'null'){
            brk_time = brk_time + parseInt(vars.brk_time2);
        }
       
        //console.log('vars.brk_time+++'+brk_time);
        if(vars.brk_time3 != '' && vars.brk_time3 != null && vars.brk_time3 != 'null'){
            brk_time = brk_time + parseInt(vars.brk_time3);
        }
       
        //console.log('vars.brk_time==='+brk_time);
        //let brk_time = parseInt(vars.brk_time1)+parseInt(vars.brk_time2)+parseInt(vars.brk_time3);
        
        ////console.log('vars.brk_time2-->'+vars.brk_time2);
        ////console.log('vars.brk_time3-->'+vars.brk_time3);
        //console.log('brk_time-->'+brk_time);

        if(brk_time != ''){
            const hrs = Math.floor(brk_time / 60);
            const mins = brk_time % 60;

            //console.log('total_brk_time-->'+hrs+':'+mins);
            vars.$total_brk_time.val(hrs + ' : ' + mins);
        }
        else{
            vars.$total_brk_time.val('00 : 00');
        }
        
        if(vars.first_ampm_split[1] == 'AM'){
            //console.log('---');
            if(vars.first_hour_split[0] == 12){
                //console.log('+++');
                vars.first_hour_split[0] = 0;
            }
        }
        else{
            //console.log('##');
            if(vars.first_hour_split[0] == 12){
                vars.first_hour_split[0] = vars.first_hour_split[0];
            }
            else{
                vars.first_hour_split[0] = 12+parseInt(vars.first_hour_split[0]);
            }
        }

        if(vars.second_ampm_split[1] == 'AM'){
            if(vars.second_hour_split[0] == 12){
                vars.second_hour_split[0] = 0;
            }
        }
        else{
            if(vars.second_hour_split[0] == 12){
                vars.second_hour_split[0] = vars.second_hour_split[0];
            }
            else{
                vars.second_hour_split[0] = 12+parseInt(vars.second_hour_split[0]);
            }
        }

        //console.log('1st hour--'+vars.first_hour_split[0]);
        //console.log('2nd hour--'+vars.second_hour_split[0]);
        
        /*if(isNaN(vars.first_hour_split[0]) == true){
            //console.log('1st time hour NaN');
            vars.first_hour_split[0] = 0;
        }

        if(isNaN(vars.first_hour_split[1]) == true){
            //console.log('1st time min NaN');
            vars.first_hour_split[1] = 0;
        }

        if(isNaN(vars.second_hour_split[0]) == true){
            //console.log('2nd time hour NaN');
            vars.second_hour_split[0] = 0;
        }
        if(isNaN(vars.second_hour_split[1]) == true){
            //console.log('2nd time min NaN');
            vars.second_hour_split[1] = 0;
        }*/

        if (parseInt(vars.first_hour_split[0]) < parseInt(vars.second_hour_split[0]) && parseInt(vars.first_hour_split[1]) < parseInt(vars.second_hour_split[1])) {
            //console.log('1if');
            //As for the addition, the subtraction is carried out separately, column by column.
            hours = parseInt(vars.second_hour_split[0]) - parseInt(vars.first_hour_split[0]);
            minute = parseInt(vars.second_hour_split[1]) - parseInt(vars.first_hour_split[1]);

            let _hours = '';
            let _minute = '';
            let _brkmin = '';

            if (hours < 10) {
                _hours ='0' + hours;
            } else {
                _hours = hours;
            }

            if (minute < 10) {
                _minute = '0' + minute;
            } else {
                _minute = minute;
            }

            if(vars.brk_time1 != 0){
                if(_minute >= vars.brk_time1){
                    _minute = _minute - vars.brk_time1;
                    //console.log('brk if brk_time1:'+_hours+'---'+'_minute:'+_minute);
                }
                else{
                    //console.log('brk else---:'+_minute+'---'+vars.brk_time1);
                    _hours = _hours - 1;
                    _brkmin = vars.brk_time1 - _minute;
                    _minute =  60 - Math.abs(_brkmin);

                    //console.log('brk_time1 else _hours:'+_hours+'---'+'_minute:'+_minute);
                }
            }

            if(vars.brk_time2 != 0){
                if(_minute >= vars.brk_time2){
                    _minute = _minute - vars.brk_time2;
                    //console.log('brk if brk_time2:'+_hours+'---'+'_minute:'+_minute);
                }
                else{
                    //console.log('brk else+++:'+_minute+'---'+vars.brk_time2);
                    //console.log('_hours brk_time2 else:'+_hours);
                    _hours = _hours - 1;
                    _brkmin = vars.brk_time2 - _minute;
                    _minute =  60 - Math.abs(_brkmin);

                    //console.log('brk_time2 else _hours:'+_hours+'---'+'_minute:'+_minute);
                }
            }

            if(vars.brk_time3 != 0){
                if(_minute >= vars.brk_time3){
                    _minute = _minute - vars.brk_time3;
                    //console.log('brk if brk_time3:'+_hours+'---'+'_minute:'+_minute);
                }
                else{
                    //console.log('brk else***:'+_minute+'---'+vars.brk_time3);
                    _hours = _hours - 1;
                    _brkmin = vars.brk_time3 - _minute;
                    //console.log('_brkmin else:'+_brkmin);
                    
                    //console.log('_brkmin:'+_brkmin);
                    _minute =  60 - Math.abs(_brkmin);

                    //console.log('brk_time3 else _hours:'+_hours+'---'+'_minute:'+_minute);
                }
            }

            if(isNaN(_hours) == true){
                //console.log('_hours NaN');
                _hours = 0;
            }

            if(isNaN(_minute) == true){
                //console.log('_minute');
                _minute = 0;
            }

            vars.$el.val(_hours + ':' + _minute)

        }else if (parseInt(vars.second_hour_split[0]) > parseInt(vars.first_hour_split[0])) {
            //console.log('else if 2');
            if (parseInt(vars.second_hour_split[1]) < parseInt(vars.first_hour_split[1])) {
                //console.log('if 2');
                // As before we subtract column by column ... and we realize that it's impossible because our minute in second hour is greater than our minute in first hour
                // We will transform 1 hour in 60 minutes
                let _hours = parseInt(vars.second_hour_split[0]) - 1;
                let _minute = parseInt(vars.second_hour_split[1]) + 60;
                let final_hours = '';
                let final_min = '';
                let _brkmin = ''; 

                hours = _hours - parseInt(vars.first_hour_split[0]);
                minute = _minute - parseInt(vars.first_hour_split[1]);

                if (hours < 10) {
                    final_hours = '0' + hours;
                } else {
                    final_hours = hours;
                }

                if (minute < 10) {
                    final_min = '0' + minute;
                } else {
                    final_min = minute;
                }

                if(vars.brk_time1 != 0){
                    if(final_min >= vars.brk_time1){
                        //console.log('brk if');
                        final_min = final_min - vars.brk_time1;
                        //console.log('brk if brk_time1:'+final_hours+'---'+final_min);
                    }
                    else{
                        //console.log('brk else@@@:'+final_min+'---'+vars.brk_time1);
                        //console.log('_hours brk_time1 else:'+final_hours);
                        final_hours = final_hours - 1;
                        _brkmin = final_min - vars.brk_time1;
                        final_min =  60 - Math.abs(_brkmin);
                    }
                }

                if(vars.brk_time2 != 0){
                    if(final_min >= vars.brk_time2){
                        final_min = final_min - vars.brk_time2;
                        //console.log('brk if brk_time1:'+final_hours+'---'+'_minute:'+final_min);
                    }
                    else{
                        //console.log('brk else!!!:'+final_min+'---'+vars.brk_time2);
                        //console.log('_hours brk_time2 else:'+final_hours);
                        final_hours = final_hours - 1;
                        _brkmin = final_min - vars.brk_time2;
                        final_min =  60 - Math.abs(_brkmin);
                    }
                }

                if(vars.brk_time3 != 0){
                    if(final_min >= vars.brk_time3){
                        //console.log('brk if');
                        final_min = final_min - vars.brk_time3;
                    }
                    else{
                        //console.log('brk else###:'+final_min+'---'+vars.brk_time3);
                        final_hours = final_hours - 1;
                        _brkmin = final_min - vars.brk_time3;
                        //console.log('_brkmin else:'+_brkmin);
                        
                        //console.log('_brkmin:'+_brkmin);
                        final_min =  60 - Math.abs(_brkmin);
                    }
                }

               /* if (final_hours < 10) {
                    final_hours = '0' + final_hours;
                } else {
                    final_hours = final_hours;
                }

                if (final_min < 10) {
                    final_min = '0' + final_min;
                } else {
                    final_min = final_min;
                }*/

                if(isNaN(final_hours) == true){
                    //console.log('final_hours NaN');
                    final_hours = 0;
                }

                if(isNaN(final_min) == true){
                    //console.log('final_min');
                    final_min = 0;
                }

                vars.$el.val(final_hours + ':' + final_min)
            }

            if (parseInt(vars.second_hour_split[1]) === parseInt(vars.first_hour_split[1])) {
                hours = parseInt(vars.second_hour_split[0]) - parseInt(vars.first_hour_split[0]);
                //console.log('3');
                let final_hours = '';
                let _brkmin = ''; 
                let final_min = 0;

                if (hours < 10) {
                    final_hours = '0' + hours;
                } else {
                    final_hours = hours;
                }

                if(vars.brk_time1 != 0){
                    if(final_min >= vars.brk_time1){
                        //console.log('brk if');
                        final_min = final_min - vars.brk_time1;
                    }
                    else{
                        //console.log('brk else$$$:'+final_min+'---'+vars.brk_time1);
                        final_hours = final_hours - 1;
                        _brkmin = final_min - vars.brk_time1;
                        //console.log('_brkmin:'+_brkmin);
                        final_min =  60 - Math.abs(_brkmin);
                    }
                }

                if(vars.brk_time2 != 0){
                    if(final_min >= vars.brk_time2){
                        //console.log('brk if');
                        final_min = final_min - vars.brk_time2;
                    }
                    else{
                        //console.log('brk else%%%:'+final_min+'---'+vars.brk_time2);
                        //console.log('_hours brk_time2 else:'+final_hours);
                        final_hours = final_hours - 1;
                        _brkmin = final_min - vars.brk_time2;
                        final_min =  60 - Math.abs(_brkmin);
                    }
                }

                if(vars.brk_time3 != 0){
                    if(final_min >= vars.brk_time3){
                        //console.log('brk if');
                        final_min = final_min - vars.brk_time3;
                    }
                    else{
                        //console.log('brk else^^^:'+final_min+'---'+vars.brk_time3);
                        final_hours = final_hours - 1;
                        _brkmin = final_min - vars.brk_time3;
                        //console.log('_brkmin else:'+_brkmin);
                        
                        //console.log('_brkmin:'+_brkmin);
                        final_min =  60 - Math.abs(_brkmin);
                    }
                }

                if (final_min < 10) {
                    final_min = '0' + final_min;
                } else {
                    final_min = final_min;
                }

                if(isNaN(final_hours) == true){
                    //console.log('final_hours NaN');
                    final_hours = 0;
                }

                if(isNaN(final_min) == true){
                    //console.log('final_min');
                    final_min = 0;
                }

                vars.$el.val(final_hours + ':' + final_min)
            }

        }else if (parseInt(vars.first_hour_split[0]) > parseInt(vars.second_hour_split[0])) {
            //console.log('else if 4--');
            let first_hour_only_hour = parseInt(vars.first_hour_split[0]);
            let second_hour_only_hour = parseInt(vars.second_hour_split[0]);

            let first_hour_only_min = parseInt(vars.first_hour_split[1]);
            let second_hour_only_min = parseInt(vars.second_hour_split[1]);

            let tmp_hour = 24 - first_hour_only_hour;
            let tmp_ttl_hour = tmp_hour + second_hour_only_hour;

            let tmp_ttl_min = first_hour_only_min + second_hour_only_min;
            let tmp_new_hour = 0;
            let tmp_new_min_mod = 0;

            let _hours = '';
            let _min = '';

            let _brkmin = '';

            /*if(second_hour_only_min > first_hour_only_min){
                _min = second_hour_only_min - first_hour_only_min;
            }
            if(second_hour_only_min == '00' || second_hour_only_min == '0'){
                second_hour_only_min = 60;
                _min = second_hour_only_min - first_hour_only_min;
                _hours = tmp_ttl_hour - 1;
            }*/

            //console.log('tmp_ttl_min:'+tmp_ttl_min);
            //console.log('tmp_ttl_hour---:'+tmp_ttl_hour);
            if (tmp_ttl_min > 59) {
                tmp_new_hour = parseInt(tmp_ttl_min/60);
                //tmp_new_min_mod = tmp_ttl_min%60;
                //console.log('tmp_new_hour:'+tmp_new_hour);
                //console.log('tmp_ttl_hour---:'+tmp_ttl_hour);
                //tmp_ttl_hour += tmp_new_hour;

                if(second_hour_only_min > first_hour_only_min || second_hour_only_min == first_hour_only_min){
                    _min = second_hour_only_min - first_hour_only_min;
                    _hours = tmp_ttl_hour
                }
    
            } else {
                //console.log('else:');
               /* if(second_hour_only_min == '00' || second_hour_only_min == '0'){
                    //console.log('second_hour_only_min 00:');
                    second_hour_only_min = 60;
                    _min = second_hour_only_min - first_hour_only_min;
                    _hours = tmp_ttl_hour - 1;
                }
                else */
                if(second_hour_only_min > first_hour_only_min || second_hour_only_min == first_hour_only_min){
                    /*//console.log('second_hour_only_min > ==:');
                    if(first_hour_only_hour > second_hour_only_hour){
                        _min = second_hour_only_min + first_hour_only_min;
                        temp = 24 + second_hour_only_hour;
                        _hours = temp - first_hour_only_hour;
                    }
                    else{*/
                        _min = second_hour_only_min - first_hour_only_min;
                        _hours = tmp_ttl_hour
                    //}
                }
                else if(second_hour_only_min < first_hour_only_min){
                    //console.log('second_hour_only_min < ==:');
                    _min = second_hour_only_min + first_hour_only_min;
                    if(second_hour_only_min == 0){
                        _min = 60 - (second_hour_only_min + first_hour_only_min);
                    }
                    temp = 24 + second_hour_only_hour;
                    //console.log('temp---'+temp);
                    _hours = temp - first_hour_only_hour-1;
                }
            }
      
           /* if (tmp_ttl_hour < 10) {
                _hours = '0' + tmp_ttl_hour;
            } else {
                _hours = tmp_ttl_hour - 1;
                //_hours = tmp_ttl_hour
            }*/

           /* if (tmp_new_min_mod < 10) {
                _min = '0' + tmp_new_min_mod
            } else {
                _min = tmp_new_min_mod
            }*/

            if(vars.brk_time1 != 0){
                if(_min >= vars.brk_time1){
                    //console.log('brk if');
                    _min = _min - vars.brk_time1;
                }
                else{
                    //console.log('brk else&&&:'+_min+'---'+vars.brk_time1);
                    _hours = _hours - 1;
                    _brkmin = _min - vars.brk_time1;
                    _min =  60 - Math.abs(_brkmin);

                    //console.log('_brkmin---'+_brkmin);
                }
            }

            if(vars.brk_time2 != 0){
                if(_min >= vars.brk_time2){
                    //console.log('brk if');
                    _min = _min - vars.brk_time2;
                }
                else{
                    //console.log('brk else(((:'+_min+'---'+vars.brk_time2);
                    //console.log('_hours brk_time2 else:'+_hours);
                    _hours = _hours - 1;
                    _brkmin = _min - vars.brk_time2;
                    _min =  60 - Math.abs(_brkmin);
                }
            }

            if(vars.brk_time3 != 0){
                if(_min >= vars.brk_time3){
                    //console.log('brk if');
                    _min = _min - vars.brk_time3;
                }
                else{
                    //console.log('brk else))):'+_min+'---'+vars.brk_time3);
                    _hours = _hours - 1;
                    _brkmin = _min - vars.brk_time3;
                    //console.log('_brkmin else:'+_brkmin);
                    
                    //console.log('_brkmin:'+_brkmin);
                    _min =  60 - Math.abs(_brkmin);
                }
            }



            if (_hours < 10) {
                _hours = '0' + _hours;
            } else {
                _hours = _hours;
            }

            if (_min < 10) {
                _min = '0' + _min;
            } else {
                _min = _min;
            }

            if(isNaN(_hours) == true){
                //console.log('_hours NaN');
                _hours = 0;
            }

            if(isNaN(_min) == true){
                //console.log('_min');
                _min = 0;
            }

            vars.$el.val(_hours + ':' + _min)

        } else if (parseInt(vars.first_hour_split[0]) === parseInt(vars.second_hour_split[0])) {
            //console.log('5');
            hours = '0';
            let minute = 0;
            let _brkmin = '';
            if (parseInt(vars.first_hour_split[1]) < parseInt(vars.second_hour_split[1])) {
                minute = parseInt(vars.second_hour_split[1]) - parseInt(vars.first_hour_split[1]);
            }

            if(vars.brk_time1 != 0){
                if(minute >= vars.brk_time1){
                    //console.log('brk if');
                    minute = minute - vars.brk_time1;
                }
                else{
                    //console.log('brk else===:'+minute+'---'+vars.brk_time1);
                    hours = hours - 1;
                    _brkmin = minute - vars.brk_time1;
                    minute =  60 - Math.abs(_brkmin);
                }
            }

            if(vars.brk_time2 != 0){
                if(minute >= vars.brk_time2){
                    //console.log('brk if');
                    minute = minute - vars.brk_time2;
                }
                else{
                    //console.log('brk else....:'+minute+'---'+vars.brk_time2);
                    //console.log('_hours brk_time2 else:'+hours);
                    hours = hours - 1;
                    _brkmin = minute - vars.brk_time2;
                    minute =  60 - Math.abs(_brkmin);
                }
            }

            if(vars.brk_time3 != 0){
                if(minute >= vars.brk_time3){
                    //console.log('brk if');
                    minute = minute - vars.brk_time3;
                }
                else{
                    //console.log('brk else~~~:'+minute+'---'+vars.brk_time3);
                    hours = hours - 1;
                    _brkmin = minute - vars.brk_time3;
                    //console.log('_brkmin else:'+_brkmin);
                    
                    //console.log('_brkmin:'+_brkmin);
                    minute =  60 - Math.abs(_brkmin);
                }
            }

            if (hours < 10) {
                hours = '0' + hours;
            } else {
                hours = hours;
            }

            if (minute < 10) {
                minute = '0' + minute;
            } else {
                minute = minute;
            }

            if(isNaN(hours) == true){
                //console.log('hours NaN');
                hours = 0;
            }

            if(isNaN(minute) == true){
                //console.log('minute');
                minute = 0;
            }

            vars.$el.val(hours + ':' + minute)

        }else if (parseInt(vars.first_hour_split[0]) === 0 && parseInt(vars.first_hour_split[1]) === 0) {
            hours = parseInt(vars.second_hour_split[0]);
            minute = parseInt(vars.second_hour_split[1]);

            let _brkmin = '';
            //console.log('6');
            if (hours === 0) {
                if(brk_time != 0){
                    if(vars.brk_time1 != 0){
                        if(minute >= vars.brk_time1){
                            //console.log('brk if');
                            minute = minute - vars.brk_time1;
                        }
                        else{
                            //console.log('brk else{{{:'+minute+'---'+vars.brk_time1);
                            _brkmin = minute - vars.brk_time1;
                            minute =  60 - Math.abs(_brkmin);
                        }
                    }

                    if(vars.brk_time2 != 0){
                        if(minute >= vars.brk_time2){
                            //console.log('brk if');
                            minute = minute - vars.brk_time2;
                        }
                        else{
                            //console.log('brk else}}}:'+minute+'---'+vars.brk_time2);
                            //console.log('hours brk_time2 else:'+hours);
                            _brkmin = minute - vars.brk_time2;
                            minute =  60 - Math.abs(_brkmin);
                        }
                    }

                    if(vars.brk_time3 != 0){
                        if(minute >= vars.brk_time3){
                            //console.log('brk if');
                            minute = minute - vars.brk_time3;
                        }
                        else{
                            //console.log('brk else[[[:'+minute+'---'+vars.brk_time3);
                            _brkmin = minute - vars.brk_time3;
                            //console.log('_brkmin else:'+_brkmin);
                            
                            //console.log('_brkmin:'+_brkmin);
                            minute =  60 - Math.abs(_brkmin);
                        }
                    }
                }

                vars.$el.val('00:' + minute)

            }else if (minute === 0){
                if (hours < 10) {
                    vars.$el.val('0' + hours + ':00');
                }else {
                    vars.$el.val(hours + ':00');
                }
            }else {
                if(vars.brk_time1 != 0){
                    if(minute >= vars.brk_time1){
                        //console.log('brk if');
                        minute = minute - vars.brk_time1;
                    }
                    else{
                        //console.log('brk else]]]:'+minute+'---'+vars.brk_time1);
                        hours = hours - 1;
                        _brkmin = minute - vars.brk_time1;
                        minute =  60 - Math.abs(_brkmin);
                    }
                }

                if(vars.brk_time2 != 0){
                    if(minute >= vars.brk_time2){
                        //console.log('brk if');
                        minute = minute - vars.brk_time2;
                    }
                    else{
                        //console.log('brk else|||:'+minute+'---'+vars.brk_time2);
                        //console.log('hours brk_time2 else:'+hours);
                        hours = hours - 1;
                        _brkmin = minute - vars.brk_time2;
                        minute =  60 - Math.abs(_brkmin);
                    }
                }

                if(vars.brk_time3 != 0){
                    if(minute >= vars.brk_time3){
                        //console.log('brk if');
                        minute = minute - vars.brk_time3;
                    }
                    else{
                        //console.log('brk else<<<:'+minute+'---'+vars.brk_time3);
                        hours = hours - 1;
                        _brkmin = minute - vars.brk_time3;
                        //console.log('_brkmin else:'+_brkmin);
                        
                        //console.log('_brkmin:'+_brkmin);
                        minute =  60 - Math.abs(_brkmin);
                    }
                }

                if (hours < 10) {
                    hours = '0' + hours;
                } else {
                    hours = hours;
                }

                if (minute < 10) {
                    minute = '0' + minute;
                } else {
                    minute = minute;
                }

                if(isNaN(hours) == true){
                    //console.log('hours NaN');
                    hours = 0;
                }

                if(isNaN(minute) == true){
                    //console.log('minute');
                    minute = 0;
                }

                vars.$el.val(hours + ':' + minute)
            }
        }
    };


    /* END PUBLIC FUNCTION */

    this.construct(options);
};


const differenceHours = new DifferenceHours();
