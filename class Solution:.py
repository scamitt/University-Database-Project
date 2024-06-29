class Solution:
    def heu(self,cur,end):
        cost=0
        for i in range(len(cur)):
            cost+=abs(int(end[i])-int(cur[i]))
        return cost

    def gen_neighs(self,num):
        neighs=[num[:3]+str(int(num[3])+1),num[:2]+str(int(num[2])+1)+num[3],num[0]+str(int(num[1])+1)+num[2:],str(int(num[0])+1)+num[1:]]
        if int(num[3])-1>0:
            neighs.append(num[:3]+str(int(num[3])-1))
        elif int(num[2])-1>0:
            neighs.append(num[:2]+str(int(num[2])-1)+num[3])
        elif int(num[1])-1>0:
            neighs.append(num[0]+str(int(num[1])-1)+num[2:])
        elif int(num[0])-1>0:
            neighs.append(str(int(num[0])+1)+num[1:])
        return neighs

    def openLock(self, deadends, target: str) -> int:
        start ="0000"
        queue=[(self.heu(start,target),start,[start])]
        while queue:
            queue.sort()
            cost,node,path=queue.pop(0)
            print(path)
            if node==target:
                return len(path)-1
            neighbors=self.gen_neighs(node)
            for neigh in neighbors:
                if neigh not in path and neigh not in deadends:
                    queue.append((cost+self.heu(neigh,target),neigh,path+[neigh]))
        return -1
            
s=Solution()
s.openLock(["8887","8889","8878","8898","8788","8988","7888","9888"],"8888")
# print("9999"-"0000")