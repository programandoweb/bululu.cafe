import React from 'react';
import TextLogo from '../logo/Texto';
import AddressComponent from '../address';
import SubNavComponent from '../subnav';
import Link from 'next/link';

interface FooterProps {
    name: string;
}

const Footer: React.FC<FooterProps> = ({ name }) => {
  return (
    <footer className="p-10 md:p-20 bg-gray-800  shadow-top">
      <div className="max-w-screen-3xl mx-auto">
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 text-white justify-center items-center">
          <div className="flex justify-start md:justify-center items-start md:items-center pb-2 md:pb-0 border-b-2 border-indigo-500 md:border-b-0">
            <SubNavComponent/>                        
          </div>    
          <div className="flex justify-start md:justify-center items-start md:items-center">            
            <AddressComponent/>            
          </div>    
          <div className="flex justify-center items-center">
            <div className='mt-4 md:mt-0'>
              <TextLogo name={name} textColor1="text-white" textColor2="text-gray-100"/>
              <div className='text-center text-gray-600'>
                  <Link href="https://programandoweb.net" target='_blank'>
                    By {process.env.NEXT_PUBLIC_GENERATOR }
                  </Link>
              </div> 
            </div>            
          </div>    
        </div> 
      </div>  
                  
    </footer>
  );
};

export default Footer;
