<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IPHash {

    public $ip;

    /**
     * te ciągi zostaną użyte do transformacji adresu IP na ciąg zbliżony
     * do soli, której będziemy używać - a każde użycie danej cyferki będzie
     * nam zwiększało licznik o jeden, powodując użycie kolejnego ciągu i tak
     * dalej i tym podobne...
     */
    protected $digits = array(
        '0' => array(
                   'tkzEB6J2}lmF6G0R',
                   'b;siVG|a]zxB,xfB',
                   'b[iiUq(7P{)pdqi4',
                   'rnYE9dWuVp0{|-:2',
                   'BpKScsWcpU4f#f87',
                   'xg5BnJ#UF,6#kbdH',
                   'nMycc:zQ*Q5y9yZ]',
                   't}c]9PGV!g1OLX||',
                   '5KG5U#8e4m+WEWqL',
                   'gb#FVwXxWgz;zt3z',
                   'Ojb,A3I;3QpWBVzY',
                   'Le9Mijbe{-bNGV7D',
                   'ZPnHveJ7#VOx1vy0',
                   '}-Fumz[pAr8!aZ2)',
                   '[Wq#KYBUj}zdrbo)',
               ),
        '1' => array(
                   'NtDiiVNEN|cym(y2',
                   'R(k)lr+8GnFmNjWF',
                   'k(4yVR37QQ|:op]]',
                   ';tVN(RgCq}ZBw94!',
                   '{XuU8piQKG5xh!5Q',
                   'v.782S9j6Wh3:.c)',
                   ')QRRmU6.6[:|SV[g',
                   'LfjH2vaXMSiKPF(P',
                   '}k{q8[U0t96L3QwD',
                   'nd3u#dvlUo]#Xz;:',
                   'pMDVV+jSL9#8el-B',
                   '+#Nnlo*FTSI#UyIL',
                   'UOVahvpAq]gK-O2b',
                   '7i[+JE+BIX!JICI{',
                   '.sb.m.mK1HA{IR+I',
               ),
        '2' => array(
                   '8qbhu8{].#T1-)9t',
                   'Wb]yhe]wDRhvUxtQ',
                   '.DWjARjDhpu:e2|4',
                   'uoP3UbFnG!)7WhWz',
                   't3G3T1JJ7#2j3aZq',
                   ',F6j8|w+At9uPv.o',
                   'TI#xb3wqWYB]RS,t',
                   '3:ngmeIf+1Cy:OU)',
                   '({W,bVDcnTBsgiOC',
                   '0UA)8kggik:IvIqb',
                   'Ly{[TDYrvq4QoGf-',
                   'y6]kU8x-Bk+5Md#]',
                   'eB:S;PiMuUxQlFo;',
                   'b9rrg)!rg2kHqG+|',
                   'p#Lmu|jIjZWr]fnn',
               ),
        '3' => array(
                   '8azDl;{}!uEx0K!g',
                   'q.ONJtbfQ(+ZFNdg',
                   'dMx2gqnS4#lsApno',
                   'hYHPk2MK,rOgxVL!',
                   '!YlHUmTJ8T-4C]QB',
                   'RM3s!m+PX)g7K((d',
                   '60Sz70-b2KBr7n)2',
                   '1bob1nZwx[q,ivTg',
                   'B(.wlpWBycGv}GPI',
                   'b:Gm16}j3VrEz1u1',
                   '9DY;7wuC[BCFbAl|',
                   ')8lD!{;yAshy)j0x',
                   '*{x{TH.e3B024Xz{',
                   'CX*8k.LT:WQy#i9-',
                   'k!3*g65NXcoEY:!D',
               ),
        '4' => array(
                   'NB8RIaMP.]0Ds-9a',
                   'FL:UlK2YtLJusCx)',
                   'OI;2tTEmr*]*Yq-W',
                   'm69Hg||,)Vpo#hi2',
                   'Gr44:m.QOt!44zqq',
                   '0G,YTyf|,)20)TLa',
                   '.fzXSMjWy+;daNOS',
                   'Eun47AA9!xFozf{Q',
                   'jx]G]OA!CaJV|CzW',
                   'KnK1k|isn5Lsi:Kg',
                   ';T3S]rdpUXKm6UD{',
                   'G,tP6*+tQoGCJ4fD',
                   'cRX(S]y(auBKB2Wk',
                   '4i95TTB;#o4.wPtC',
                   'wqsx:k-Z]Y)K;1dx',
               ),
        '5' => array(
                   'tlOAfGH(N5sBE2xb',
                   '*W#7tA.VQbdl.RcP',
                   'O5WikWE3iyNr[6sI',
                   '+c*sI4!t7,4Tp9UJ',
                   'v{UXf13dv+ulk]cQ',
                   '{1|ujNmXpvWaLGAe',
                   '91JmS-tWzX6,mLF{',
                   'h|kB},MbC,o1tquG',
                   'Nc.I6LOptOKlfZvp',
                   'H6M*0.z-ccz!8fIJ',
                   'TXgFk0wOdfPpP2E)',
                   'tm{70RGwvZz:Nxjn',
                   'Pr5e67:PFEL;1+ac',
                   'AkIi};].!wqsUV|Z',
                   'CTVJScPruF9+cHoa',
               ),
        '6' => array(
                   'U.dbiKle}ZZsSU-k',
                   '}VkQfRV[Dgx:+)s}',
                   'dqpwHxnarc89Fu-C',
                   'XzsyjfSj[PRoF-mh',
                   'exsXspfzY.i|*az8',
                   '(jqmeBF;lZyiSTZp',
                   'YKlZ9,Pc5w.-cjfT',
                   'Gve!S9*2p-;)UDAC',
                   'G:X}Ri.hd.IZ09D5',
                   'WNLY5XaKj{OiNRp3',
                   '|P9l[ja4(dSPP-o8',
                   'aG#WjA-)V8Tm7);J',
                   'S+lx[2(!;]SgeE4b',
                   'YaRu5GM*7rP8s{W}',
                   'u)5OQwC6IxJElyLM',
               ),
        '7' => array(
                   'LBq[RobNkrB(K;i{',
                   '3JPG9*OlG]2EjxUZ',
                   '.E+febh649|7nymE',
                   'B.aarXQh7elfYCT2',
                   'fi43jreFP}Vl,}h*',
                   'D6dAGPI)z1BNC(J*',
                   '2T2}eMTrb0}f9LU3',
                   '6dYCii2iWydWv21G',
                   'Pd}7RJ9ie]D!A76a',
                   '{TIg|Imo)1x*Haj#',
                   'd7hn5ho}[RSf4JkR',
                   '[j(Q.KD;Kf*xgR9*',
                   'j0EkjMAaKjYjzy5h',
                   '0FXa.mg,ab+4xi[Q',
                   'fKn.5S[a,IT5[sRE',
               ),
        '8' => array(
                   'SD}3|W|Bizym#FqA',
                   'U*2oR{p(]IF}23dC',
                   'lcA+hg1shbfm}E{N',
                   '9-Ivdmvm)k(4Vqhc',
                   'wINFG83oYgF3pfz|',
                   'oSPHi}swFS[ipubJ',
                   'xQQ}r0*1D3qvVMou',
                   ';BO7dYfMSa)c|DOR',
                   'ozJ#8Jppx{8#1VW5',
                   'YOCLRULDW2H{#.1{',
                   'Yn4LP(PvK3QT[(4r',
                   'm|(!4s}YQ#WcwBep',
                   '8#q6Z)}Gkh[Jzudu',
                   'su]|iXO7AAagfq0*',
                   'i[IGZNJ4!c]a41(D',
               ),
        '9' => array(
                   'o4-D4cm8yvX2GkLc',
                   '0YmtP7cI.|S:;iA.',
                   'bqO(Q.![RvJzu*Zb',
                   'Jx,l8Goy-d;:8hJA',
                   'Gsd0dy,IwB-1Bib}',
                   'Uc:*qtquziu]+-Io',
                   'xAlYi2)S7(cfXmYh',
                   'VjBfFki7e4JHmp:i',
                   'I+jLdht#I3.]h7Jp',
                   'M86hg(SwB}lQIy}#',
                   'g8[aQeZvMcMxk#*D',
                   '6+|1{d2}l5(|rM[e',
                   'vkE84#43FV][c5]3',
                   'qBbv,fdN1j4r6B-i',
                   '|dx5hhan2nedNi)J',
               ),
        '.' => array(
                   'Gu5*,-+;He4-LUP2',
                   'yzKT3n{ooMa.-cG}',
                   'isZYzrFOQ!*gS1fy',
                   'k[k9Q;56GMOwa{U|',
                   '!7uQg!R(OfogP0p.',
                   'Pb|Ec,;zln2-(aYE',
                   '2x)kFTT|DkN,DRob',
                   ')*,mhC8-+WgbDeS*',
                   '3#,LqLLC)6zp(8dX',
                   'Rq*act*[:e3YP.D3',
                   ')R!N7e5!c#Fy]FLs',
                   'p}vqPplRB5QuRw}b',
                   'oFmqOEp+27ZN-p(U',
                   '#UaM33Gj7+dXK,VS',
                   'OXO]YFGTSlhDMeUX',
               ),
    );

    /**
     * tutaj przedstawiamy zestaw soli, które zostaną użyte - której soli użyć, będzie
     * zależało od dwóch ostatnich cyfr adresup ajpi. Sól po lewej stronie będzie wybierana
     * według ostatniej cyfry (pozycja 0) a sól po prawej - według przedostatniej cyfry
     * (pozycja 1) - tym sposobem, gmatwamy sprawę jeszcze bardziej - mam nadzieję.
     */
    protected $salts = array(
        '0' => array(
                   '2tpej}chZAg:#8,QsmI0RNdLh3W*wL2Aw3#m+sAo6TS,dXnmE}kdR;Qy#FVD;f)#+CZlk]Am5adlgnu|2,T-szcGfJuO}sEbYEVap;a]W]7G0uG(G)cJ]U7]jE]P,v|2Q8sH;kJFBR1ovs6{yt4Wj{2l40hHWo!T',
                   'j}W{,2SyK9L1*hKmuY*[:BOo{n70KZKkJbaO;K!Kur,FZN}P84DT1Q1g2H*k];5uK3,oqUsjjF1LS)z(xazEIW6RfmBA8nEunWv)lV0{}gVHmTCP#{z}!Ra2]-ud67rkXq.3-Mi*ZyF6Nt!lP4GU#DNexAtyVSkT',
               ),
        '1' => array(
                   'g|e1l5i(Y*SuQ--{C;.zJ!],ZE9+;c(19!]}ro-sz*)Pu6X(S89:2Y4Hb:z-Hfea0vzhBDrYew{xd4K{,*jMA(.T+*+|p]g!JdbF-f{5nc;:q(]l)kaPlFLWtiVjdK.;(EhpBzObBAxOHj7PIGvJbV-O)BKIv+uJ',
                   '*q45r-E}2l{UAG4#,oU5v[39ZhavR[)IO9}Ln-X7J1IQWpSP32VsUWZuT#9BP#X;8T6T.|6C!I4D848l]GxKUIKMp67WoDP!HckSni5;}RF#:KDzONfpw+YpR:XfUPjategHsHW.GQuZcSl#cKp0da1.V5CgaSK1',
               ),
        '2' => array(
                   'lY(-DtCp,Gn)NQ[z99FBn,+;{|KZ.LMS)Gig0:ML6--H[Epl5y8FCKIClctHz7pje!o5DSwuegkX1*LOTUQ;4Z*6y8i!4tZtfT;Vtbm9V6nb)cI#8}0*Mc0|Rb{TlLjt-w,lpb(OmX8ZF1hODE1h*[VaLzXvipAj',
                   's6Au1UcVKn!M}QMkkUTA*MG4h1V4,.5R!FrtpBbSJw41(gTzZ]AfN0VXJ5vDoAO0UHniuw#GjwhgNo|#nYL}5e-rN.aEvD.]L;0PFMk+Qla38mpzILx3D1(!swi7I81}e(p+2oucZTxRIa)kF0o7pOF)dZ7|8#U:',
               ),
        '3' => array(
                   'FeuWF+I1,1ZewHaNdg+0fb#yq2+]NdDHG6Fwv6:Qyaakp{Z!I4gwzh,hJYC1)7PHj[32q7Rf5}*h.NxNOShZYcSIDtvT#a}9TtH;3,0mn9rVkmbkwg.1YQDv]*tmHwu}c#y9rq.jmG#:n#iyjnsW{4SDB.9x,,4]',
                   '4)u-wQQZ2FWp7.6!zk!rgj*ZF){y50o.+y7pG|8Dt7PA}-(yUZS(q5}jkq+pb(rRFgvN4HGv}zk[2y:jRCJ+FSClXTWZRo:i|H|xH]qIQXMnecqNouc}8f|ei,K9hQ#s!uMBxjggF*QtZ;n!UGBmQl)PBSajD#4s',
               ),
        '4' => array(
                   'JB:TskzxYMuqti2N[eCO.gP07o91BMfi3WO]5bY(6cecV-rj*ue!.UAbT}8Kah[QCf#[tKX}5lrHXDyyu|OMQ9P*5)XVGut.ph{Py(yqVlJy|LK}-yYh9n#F9{CWOwU[h]C6IUeWBaDJkJ:xel-uH:zRclKNxqW|',
                   '}J}o6wo2JIZhx:OjaQ|ysW;TPOu7l]z8a)+BJ4C]t};xO{)M{+:sjkyMb!ir}BiGbXTbzD4zjRD!HX#}dUtc!p!75N{HZpuG){FtuDL4|rhzc)GMjsuE]lMs0iXkW9L8o9U}76)AZ-TlDK+i7+AtaZ9JLwW#vUD|',
               ),
        '5' => array(
                   'zI.|vz(9Y7eQG]3x)3(B!BMn-{w3aeZZPK*p{YK,y68*kh]}:04Zkr|,1va4hh9-HCIXK-2ne+2SVfOxpzekuCw9Xgj+6a;Pa+,48QAJmoNqfVdimcXnJ]8;HI8p)MdhAh2cKikrAM.)9A;M0:phAov4Vy,P;-Nq',
                   'C:Z#|sa62w202t8|pgPTTt{z,HD|saZqebM:wstLIBW+MGWLv)Q62pJ[#dwIq)0hOw]G9jSS}TGu,fAiH:si3b61Drua5E#P[*6FwSRQ|bTZ0xHw5F]3UFJV}v:lvRC-0,26|1sEFa+4U(p|JCkOBvyp)p|m!UzZ',
               ),
        '6' => array(
                   '#|PxMb[),R|aD!dw|1;aLGt|Pa,ePN|{Bj3V9(ia59AzPrthxq3dwd5!;DWND9I7xK9#MB8-dry3!YPL;MLZmsswNsL,Vu3FyxeI}L-NVG|9w5M|{u*J)ywf}CbZ1MDFFES]yMFclgK56clL*8**dtGGwZ:}.rq7',
                   '.Zgh:C{jJz5#s!RhSMN[df3t7l8:O1iz:iAgy*zhI+Ggu9YwFT7(*JFBupYJ4C!Ylk8X!fEXi*vBCF)|VQFEo{vWykDLAFc,ccWCO6+BQxoj,Dtw0DrRyKqhHXLCj:eOxM:v;Y6tZd.+:3-h,#iVNh2j39u+Z(-g',
               ),
        '7' => array(
                   'X,EJOqeF,BlAlueKL[!PDwpcFnjfofspZ[lj#{WeyloGeQb{0+a;X}tMu8|8L9#p}!Z|{|nv429C|{|K6Kbx6yD-oUY[IbVfkAm-AsM}mI(1aDfhc[(Q6j0].gWthyNOvLH+{]L{lS6qdUmvRfmuYOC(mkw#O.h+',
                   'LNDx(:mn1RiL,a:{fcx5E#sCqeb(V2IXDFadPkUTtGbss[-vARNHOPnO;Rjw#*KzY.FAB1c0}-y5#PCg96EK{m.R*49esqn68kLkQJ2YQg[JW5uKWmYEq|mJ9h,E,SYE9Br0)U#xF7cPPmr{;:80-)q4m3Dqe)hE',
               ),
        '8' => array(
                   'aeeG|U;2XX+Lj;6KGY9nh}ApgOw8cO;6KztOCLt95os:K]wgnm]Fho,H!avR!j3yV-ON}4t74x(zNq}uatIk.S;kee:ahvMX+0KC*n:)UwXUryPzqqT*r+p)i]|bpy,+z#h(RD}kAmR|awCuMd(8|Tk63M,UBs.N',
                   '8mLbDf.HJUlCmkTgIIwAQq+9z.XC1bTetNR0Sz[pt2NSER.Jg{46H(j11cLyHDBou{yQ+,Q4iaRY9-+zLMo}]-nwe,bOzrNoBHkh!,hJ{!vVTXKJRkoc:)9X[J:qx;{e*a{pE{FQY:fH5w0fz{77:s).vX9.AVsN',
               ),
        '9' => array(
                   '6lVo#hFNXHs+727um*{a]W8SY]Z:}g2*gnRd,HgBxGKb]:dz}#-xI1mmaVD;-jnW[foKTsO!d7E-K8TuXr[-SXhy*GM2XcpxtPS!F:XZmz]MX+hfUWR2!u(|r(vwLO85T0YRUS++AXB]-nDJs2k+u,gt(YkWa0!C',
                   'Vb(r-ayL1+Ef!KAyX[Js13kcY#}+9b2Cx)r,(b5aIMicCt]IbyKUk-6DSDZO6+d5fD*6GTnh|dUQz8Ef-2m+lw!rnRXhVGYxqO]4)Bne{mf5}OLtx9WR2[5:QVxsATrWHQX(.w4UFl:kKWuy16emv5jUUQctHWS*',
               ),
    );

    public function translate_ip( $ip ) {
        $ip = preg_replace('/[^\\d.]+/', '', $ip );
        $e = explode( '.', $ip );
        $ec = count($e);
        $ost_liczba = $e[ $ec - 1 ] % 10;
        $prd_liczba = $e[ $ec - 2 ] % 10;
        $ipl = strlen( $ip );
        $ips = '';
        $licznik = array();
        for( $i = 0; $i < $ipl; $i++ ) {
            $c = $ip{ $i };
            if ( !isset( $licznik[ $c ] ) ) { $licznik[ $c ] = 0; } else { $licznik[ $c ]++; }
            if ( $licznik[ $c ] == 15 ) { $licznik[ $c ] = 0; }
            $h = $this->digits[ $c ][ $licznik[ $c ] ];
            $ips.= $h;
        }
        $ciag = md5( hash( 'sha512', $this->salts[ $ost_liczba ][ 0 ] . $ips . $this->salts[ $prd_liczba ][ 1 ] ) );
        return $ciag;
    }

    public function __construct( $ip = '' ) {
        if ( $ip == '' ) { $ip = $_SERVER['REMOTE_ADDR']; }
        $this->ip = $this->translate_ip( $ip );
    }

}

# EOF