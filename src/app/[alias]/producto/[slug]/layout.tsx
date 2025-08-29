import Header from "@/components/header";
import type { Metadata } from "next";
import "../../(store)/storecss.css"
import Footer from "@/components/footer";

const name  = "Store";

export const metadata: Metadata = {
  title: process.env.NEXT_PUBLIC_NAME+' '+name,
  description: process.env.NEXT_PUBLIC_NAME,
  applicationName: process.env.NEXT_PUBLIC_NAME+' '+name,  
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return  <>
            <div className="min-h-screen flex flex-col justify-between">
              <Header name={name}/>
                <main className="mt-10">    
                  <div className="mt-10 max-w-screen-3xl mx-auto p-3 ">
                    {children}
                  </div>              
                </main>                
              <Footer name={name}/>
            </div>
          </>
}
