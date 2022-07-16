#!/usr/bin/php
<?php
require_once(__DIR__.'/configure.php');
require_once(__DIR__.'/escreva.php');
execute(IP_CGNAT);

function execute($cgnat)
{
    $rule = 0;
    $s = 1;
    $i = 1;
    $e = $cgnat;
    $ip1 = ip2long(IP_PUB_START);
    $ip2 = ip2long(IP_PUB_STOP);
    $ip3 = ip2long(IP_PRI_START);
    
    /**
     * Loop IP Pub Range
     */    
    while ($ip1 <= $ip2) {

        
        $ip = long2ip($ip1);        
       
        // 4o Octeto
        $ipx = explode('.', $ip);
        if( $ipx[3] > -1 && $ipx[3] < 256 ){
                        
            /**
             * Faixa IP Pri
             */            
            $p = ceil((65535-1024)/$cgnat);
            $port_s = 1025;
            $port_e = $port_s + $p;
            for($pri = $i; $pri <= $e; $pri++) {
                                
                // 4o Octeto
                $ipp = explode('.', long2ip($ip3));
                if($rule++ <= 65535 && $ipp[3] > -1 && $ipp[3] < 256 ){
                    {
                    }
                    write::file("set nat source rule " .$rule. " description CGNAT_".long2ip($ip3)."_TO_".$ip."\n"."set nat source rule " .$rule. " outbound-interface ".OUTBOUNT_INTERFACE."\n"."set nat source rule ".$rule." protocol tcp_udp"."\n"."set nat source rule ".$rule." source address ".long2ip($ip3)."\n"."set nat source rule ".$rule." translation address ".$ip."\n"."set nat source rule ".$rule." translation port ".$port_s."-".$port_e);
                    $port_s = $port_e + 1;
                    $port_e = $port_e + $p;
                    if($port_e > 65535){
                        $port_e = 65535;
                    }
                    
                }
                
                $ip3++;
                $s = $s+1;    

            }
            $i = $s;
            $e = $e+$cgnat;
        }
        
        $ip1 ++;
        
    }
    
}
