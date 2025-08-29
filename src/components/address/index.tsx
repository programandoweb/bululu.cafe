import { MdOutlineMapsHomeWork } from "react-icons/md";
import { MdWhatsapp } from "react-icons/md";
import { MdAlternateEmail } from "react-icons/md";
const AddressComponent=()=>{
    return  <div className="w-3/4">
                <ul className="list-none ">
                    <li className="flex items-center">
                        <MdOutlineMapsHomeWork className="h-5 w-5 mr-3"/>Dg 25F 17T 129 local 3, Dosquebradas 
                    </li>                
                    <li className="flex items-center">
                        <MdAlternateEmail className="h-5 w-5 mr-3"/>info@bululu.cafe
                    </li>
                    <li className="flex items-center">
                        <MdWhatsapp className="h-5 w-5 mr-3"/>+573115000926
                    </li>
                </ul>
            </div>
}
export default AddressComponent;