from pathlib import Path
import xml.etree.ElementTree as ET
import math
from PIL import Image, ImageDraw, ImageFont

out=Path(__file__).parent
tree=ET.parse(out/'B_Use_Case_WebApotek.drawio')
S=2
def font(size,bold=False):
    return ImageFont.truetype('C:/Windows/Fonts/'+('arialbd.ttf' if bold else 'arial.ttf'),int(size*S))
for index,page in enumerate(tree.getroot(),1):
    im=Image.new('RGB',(1400*S,980*S),'white');d=ImageDraw.Draw(im)
    cells={c.get('id'):c for c in page.findall('.//mxCell')}
    def box(c):
        g=c.find('mxGeometry');return [float(g.get(k,0)) for k in ['x','y','width','height']]
    def line(points,fill='#475569',width=1.6,dashed=False):
        pts=[(x*S,y*S) for x,y in points]
        if not dashed:d.line(pts,fill=fill,width=int(width*S));return
        for (x,y),(xx,yy) in zip(pts,pts[1:]):
            length=math.hypot(xx-x,yy-y)
            for start in range(0,int(length),18):
                end=min(start+10,length)
                d.line([(x+(xx-x)*start/length,y+(yy-y)*start/length),(x+(xx-x)*end/length,y+(yy-y)*end/length)],fill=fill,width=3)
    def text(value,x,y,size=16,bold=False,anchor='mm',width=270):
        f=font(size,bold);lines=[]
        for raw in value.split('\n'):
            current=''
            for word in raw.split():
                test=(current+' '+word).strip()
                if d.textlength(test,font=f)>width*S and current:lines.append(current);current=word
                else:current=test
            lines.append(current)
        spacing=size*1.35
        first=y-(len(lines)-1)*spacing/2 if anchor=='mm' else y
        for k,t in enumerate(lines):d.text((x*S,(first+k*spacing)*S),t,font=f,fill='#1e293b',anchor=anchor)
    # Border and associations behind the independently editable nodes.
    x,y,w,h=box(cells['system']);d.rectangle((x*S,y*S,(x+w)*S,(y+h)*S),outline='#334155',width=3)
    text(cells['system'].get('value'),x+w/2,y+30,19,True,width=800)
    for c in cells.values():
        if c.get('edge')!='1':continue
        source,target=c.get('source'),c.get('target')
        a,b=cells[source],cells[target];ax,ay,aw,ah=box(a);bx,by,bw,bh=box(b)
        inc='dashed=1' in c.get('style','')
        if inc:
            points=[(ax+aw,ay+ah/2),(bx,by+bh/2)]
            line(points,dashed=True);xx,yy=points[-1];line([(xx-13,yy-7),(xx,yy),(xx-13,yy+7)])
            text(c.get('value'),(ax+aw+bx)/2,ay+ah/2-18,15,width=150)
        elif index==1 and source=='customer' and target in ['uc02','uc03','uc04']:
            offset={'uc02':0,'uc03':15,'uc04':30}[target]
            points=[(ax+aw/2,ay+ah/2),(205,ay+ah/2),(205,785+offset),(660+offset,785+offset),(660+offset,by+bh/2),(bx,by+bh/2)]
            line(points)
        else:
            right=ax>bx
            sx=ax+aw/2;sy=ay+ah/2;tx=bx+bw if right else bx;ty=by+bh/2
            lane=1145 if right else (220 if source=='customer' else 185)
            line([(sx,sy),(lane,sy),(lane,ty),(tx,ty)])
    for id,c in cells.items():
        if c.get('vertex')!='1' or id=='system':continue
        x,y,w,h=box(c);style=c.get('style','');value=c.get('value','')
        if 'ellipse' in style:
            d.ellipse((x*S,y*S,(x+w)*S,(y+h)*S),fill='#f4f7fa',outline='#334155',width=3)
            text(value,x+w/2,y+h/2,16,width=w-35)
        elif 'umlActor' in style:
            cx=x+w/2
            d.ellipse(((cx-13)*S,y*S,(cx+13)*S,(y+26)*S),fill='white',outline='#334155',width=3)
            line([(cx,y+26),(cx,y+62)],width=2)
            line([(cx-30,y+40),(cx+30,y+40)],width=2)
            line([(cx-27,y+92),(cx,y+62),(cx+27,y+92)],width=2)
            text(value,cx,y+h+20,16,width=190)
        elif id=='title':text(value,x+w/2,y+h/2,26,True,width=1300)
        else:text(value,x,y,15,anchor='la',width=w)
    path=out/f'Use_Case_{index:02d}.png';im.save(path)
    print(path)
