import {Injectable} from "@angular/core";

@Injectable()
export class PermissionUtil {
    constructor() {
    }

    // Put your util helper function here
    can(permission: string): Promise<boolean> {

        // Change promise to observable
        return new Promise<boolean>((resolve, reject) => {
            //     this.storage.get('permissions')
            //         .then(val =>
            //         {

            //             if (val !== null)
            //             {

            //                 // Check is permission exist
            //                 if (typeof val.find(object => object === permission) !== "undefined")
            //                     resolve(true)
            //                 else
            //                     reject(false)

            //             }
            //             else reject(false)

            //         })

        })
    }

}
