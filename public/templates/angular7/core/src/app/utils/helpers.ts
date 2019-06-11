import {DatepickerOptions} from "ng2-datepicker";

export class Helpers {
    static getPage(totalPages: number, currentPage: number = 1) {
        // ensure current page isn't out of range
        if (currentPage < 1) {
            currentPage = 1;
        } else if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        let startPage: number, endPage: number;

        if (totalPages <= 10) {
            // less than 10 total pages so show all
            startPage = 0;
            endPage = totalPages;
        } else {
            // more than 10 total pages so calculate start and end pages
            if (currentPage <= 6) {
                startPage = 0;
                endPage = 10;
            } else if (currentPage + 4 >= totalPages) {
                startPage = totalPages - 9;
                endPage = totalPages;
            } else {
                startPage = currentPage - 5;
                endPage = currentPage + 4;
            }
        }

        // console.log('start page = ' + Array.from(Array(endPage + 1 - startPage).keys()).map(
        //   i => startPage + i
        // ));

        return Array.from(Array(endPage - startPage).keys()).map(
            i => startPage + i
        );
    }
}
